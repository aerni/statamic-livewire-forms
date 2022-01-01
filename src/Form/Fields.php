<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Str;
use Statamic\Fields\Validator;
use Illuminate\Support\Collection;
use Aerni\LivewireForms\Facades\Models;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Captcha;
use Statamic\Forms\Form as StatamicForm;

class Fields
{
    protected Collection $fields;
    protected $hydratedCallbacks = [];

    public function __construct(protected StatamicForm $form, protected string $id, protected array $data)
    {
        //
    }

    public static function make(StatamicForm $form, string $id, array $data): self
    {
        return new static($form, $id, $data);
    }

    public function data(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function all(): Collection
    {
        return $this->fields;
    }

    public function get(string $field): ?Field
    {
        return $this->fields->get($field);
    }

    public function getByKey(string $key): ?Field
    {
        return $this->fields->first(function ($field) use ($key) {
            return $field->key === $key;
        });
    }

    public function groups(): Collection
    {
        return $this->fields->groupBy(fn ($field) => $field->group);
    }

    public function group(string $group): Collection
    {
        return $this->groups()->only($group);
    }

    public function hydrate(): self
    {
        return $this
            ->hydrateFields()
            ->processFieldConditions()
            ->removeDuplicateCaptchaFields()
            ->runHydratedCallbacks();
    }

    protected function runHydratedCallbacks(): self
    {
        foreach ($this->hydratedCallbacks as $callback) {
            $callback($this);
        }

        return $this;
    }

    public function hydrated(\Closure $callback): self
    {
        $this->hydratedCallbacks[] = $callback;

        return $this;
    }

    protected function hydrateFields(): self
    {
        $this->fields = $this->form->fields()->map(function ($field) {
            $class = Models::get($field->handle()) ?? Models::get(get_class($field->fieldtype()));

            return $class ? $class::make($field, $this->id) : null;
        })->filter();

        return $this;
    }

    protected function captcha(): Collection
    {
        return $this->fields->whereInstanceOf(Captcha::class);
    }

    protected function removeDuplicateCaptchaFields(): self
    {
        $duplicates = $this->captcha()->slice(1)->keys();

        $this->fields = $this->fields->except($duplicates);

        return $this;
    }

    public function processFieldConditions(): self
    {
        $fieldsToShow = $this->fields->map(function ($field) {
            // Always show the field if there are no conditions.
            if (! $conditions = $field->conditions) {
                return true;
            }

            $type = array_key_first($conditions);
            $conditions = array_first($conditions);

            $conditions = collect($conditions)->map(function ($condition, $field) {
                [$operator, $expectedValue] = explode(' ', $condition);

                $actualValue = collect($this->data)->get($field);

                return $this->evaluateCondition($actualValue, $operator, $expectedValue);
            });

            return $this->evaluateConditions($type, $conditions);
        })->filter()->keys();

        $this->fields = $this->fields->only($fieldsToShow);

        return $this;
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

    public function realtimeValidationRules(string $field): array
    {
        $field = $this->getByKey($field) ?? $field;

        // Don't use realtime validation if the field can't be found (e.g. honeypot).
        if (is_string($field)) {
            return [$field => []];
        }

        $realtime = $field->realtime
            ?? $this->form->blueprint()->contents()['sections']['main']['realtime']
            ?? config('livewire-forms.realtime', true);

        // Use the regular validation rules if "realtime: true".
        if ($realtime === true) {
            return [$field->key => $field->rules];
        }

        // Make sure to always get an array of realtime rules.
        $realtime = Validator::explodeRules($realtime);

        // Remove any realtime rules that are not part of the regular validation rules.
        $realtime = array_intersect($realtime, $field->rules);

        return [$field->key => $realtime];
    }
}
