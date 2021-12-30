<?php

namespace Aerni\LivewireForms\Form;

use Statamic\Forms\Form as StatamicForm;
use Statamic\Fields\Field as StatamicField;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Aerni\LivewireForms\Form\Field;

class Fields
{
    protected Collection $fields;

    public function __construct(protected StatamicForm $form, protected string $id, protected array $data)
    {
        //
    }

    public static function make(StatamicForm $form, string $id, array $data): self
    {
        return (new static($form, $id, $data))->process();
    }

    public function all(): Collection
    {
        return $this->fields;
    }

    public function get(string $field): ?Field
    {
        return $this->fields->get($field);
    }

    public function groups(): Collection
    {
        return $this->fields->groupBy(fn ($field) => $field->group);
    }

    public function group(string $group): Collection
    {
        return $this->groups()->only($group);
    }

    protected function process(): self
    {
        return $this
            ->preProcess()
            ->removeDuplicateCaptchaFields();
    }

    protected function models(): array
    {
        return [
            \Aerni\LivewireForms\Fieldtypes\Captcha::class => \Aerni\LivewireForms\Fields\Captcha::class,
            \Statamic\Fieldtypes\Checkboxes::class => \Aerni\LivewireForms\Fields\Checkbox::class,
            \Statamic\Fieldtypes\Integer::class => \Aerni\LivewireForms\Fields\Input::class,
            \Statamic\Fieldtypes\Radio::class => \Aerni\LivewireForms\Fields\Radio::class,
            \Statamic\Fieldtypes\Select::class => \Aerni\LivewireForms\Fields\Select::class,
            \Statamic\Fieldtypes\Text::class => \Aerni\LivewireForms\Fields\Input::class,
            \Statamic\Fieldtypes\Textarea::class => \Aerni\LivewireForms\Fields\Textarea::class,
        ];
    }

    protected function preProcess(): self
    {
        $this->fields = $this->form->fields()->map(function ($field) {
            $class = array_get($this->models(), get_class($field->fieldtype()));

            return $class ? $class::make($field) : null;
        })->filter();

        return $this;
    }

    protected function captcha(): Collection
    {
        return $this->fields->where('type', 'captcha');
    }

    protected function removeDuplicateCaptchaFields(): self
    {
        $duplicates = $this->captcha()->slice(1)->keys();

        $this->fields = $this->fields->except($duplicates);

        return $this;
    }

    protected function getType(StatamicField $field): string
    {
        return match ($field->type()) {
            'assets' => 'file',
            'captcha' => 'captcha',
            'checkboxes' => 'checkboxes',
            'integer' => 'input',
            'radio' => 'radios',
            'select' => 'select',
            'text' => 'input',
            'textarea' => 'textarea',
            default => 'input',
        };
    }

    protected function getInputType(StatamicField $field): string
    {
        return match ($field->type()) {
            'assets' => 'file',
            'checkboxes' => 'checkbox',
            'integer' => 'number',
            'radio' => 'radio',
            'text' => $field->get('input_type') ?? 'text',
            default => 'text',
        };
    }

    protected function shouldShowField(StatamicField $field): bool
    {
        [$type, $conditions] = $this->getConditions($field);

        if ($conditions->isEmpty()) {
            return true;
        }

        $conditions = $conditions->map(function ($condition, $field) {
            [$operator, $expectedValue] = explode(' ', $condition);

            $actualValue = collect($this->data)->get($field);

            return $this->evaluateCondition($actualValue, $operator, $expectedValue);
        });

        return $this->evaluateConditions($type, $conditions);
    }

    protected function getConditions(StatamicField $field): array
    {
        $conditions = array_filter([
            'if' => $field->get('if'),
            'if_any' => $field->get('if_any'),
            'unless' => $field->get('unless'),
            'unless_any' => $field->get('unless_any'),
        ]);

        return [
            array_key_first($conditions),
            collect(array_first($conditions)),
        ];
    }

    protected function evaluateConditions(string $conditionsType, Collection $conditions): bool
    {
        return match ($conditionsType) {
            'if' => $conditions->count() === $conditions->filter()->count(),
            'if_any' => $conditions->filter()->isNotEmpty(),
            'unless' => $conditions->filter()->isEmpty(),
            'unless_any' => $conditions->count() !== $conditions->filter()->count(),
            default => false,
        };
    }

    protected function evaluateCondition(string|array|null $actualValue, string $operator, string $expectedValue): bool
    {
        return match ($operator) {
            'equals' => $actualValue == $expectedValue,
            'not' => $actualValue != $expectedValue,
            'contains' => is_array($actualValue)
                ? ! array_diff(explode(',', $expectedValue), $actualValue)
                : Str::contains($actualValue, $expectedValue),
            'contains_any' => is_array($actualValue)
                ? (bool) array_intersect(explode(',', $expectedValue), $actualValue)
                : Str::contains($actualValue, explode(',', $expectedValue)),
            '===' => $actualValue === $expectedValue,
            '!==' => $actualValue !== $expectedValue,
            '>' => $actualValue > $expectedValue,
            '>=' => $actualValue >= $expectedValue,
            '<' => $actualValue < $expectedValue,
            '<=' => $actualValue <= $expectedValue,
            default => false,
        };
    }

    public function defaultValues(): array
    {
        /**
         * When submitting a form, we need to preserve the captcha response on the Livewire component
         * until the captcha expires itself. Otherwise we will get a `The Captcha field is required.`
         * error (when submitting the form again without reloading the page) because the response value is missing.
         */
        $captcha = $this->captcha()->keys()->first();

        return $this->fields->mapWithKeys(function ($field, $handle) {
            return [$handle => $field->default];
        })->except($captcha)->toArray();
    }

    public function validationRules(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field->key => $field->rules];
        })->toArray();
    }

    public function validationAttributes(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field->key => $field->label];
        })->toArray();
    }

    // TODO: Part of this should be moved to the realtime() method in each field class.
    public function realtimeValidationRules(string $field): array
    {
        $field = $this->fields->firstWhere('key', $field) ?? $field;

        // Don't use realtime validation if the field can't be found (e.g. honeypot).
        if (is_string($field)) {
            return [$field => []];
        }

        // Don't use realtime validation for the captcha.
        if ($field->type === 'captcha') {
            return [$field->key => []];
        }

        // Get the realtime validation config from the field, form blueprint or global config.
        $realtime = $field->realtime
            // Would like to get the realtime config from the form config instead of the form blueprint, but there's currently no way to access custom data.
            ?? $this->form->blueprint()->contents()['sections']['main']['realtime']
            ?? config('livewire-forms.realtime', true);

        // Disable realtime validation if "realtime: false".
        if (! $realtime) {
            return [$field->key => []];
        }

        // Use the field validation rules if "realtime: true".
        if ($realtime === true) {
            return [$field->key => $field->rules];
        }

        // Make sure to always get an array of realtime rules.
        $realtimeRules = is_array($realtime) ? $realtime : explode('|', $realtime);

        // Remove any realtime rules that are not part of the validation rules.
        $realtimeRules = array_intersect($realtimeRules, $field->rules);

        return [$field->key => $realtimeRules];
    }
}
