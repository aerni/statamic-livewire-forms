<?php

namespace Aerni\LivewireForms\Form;

use Statamic\Forms\Form;
use Statamic\Fields\Field;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Fields
{
    protected Form $form;
    protected string $id;
    protected Collection $fields;

    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->id = Str::random(9);
    }

    public static function make(Form $form): self
    {
        return (new static($form))->process();
    }

    public function all(): array
    {
        return $this->fields->all();
    }

    public function get(string $field): array
    {
        return $this->fields->get($field);
    }

    protected function process(): self
    {
        return $this
            ->preProcess()
            ->removeDuplicateCaptchaFields()
            ->addHoneypotField();
    }

    protected function preProcess(): self
    {
        $this->fields = $this->form->fields()->map(function ($field) {
            return [
                'label' => __($field->get('display')),
                'instructions' => __($field->get('instructions')),
                'handle' => "{$this->id}_{$field->handle()}",
                'key' => 'data.' . $field->handle(),
                'type' => $this->assignFieldType($field->get('type')),
                'input_type' => $this->assignFieldInputType($field->get('type'), $field->get('input_type')),
                'options' => $this->getTranslatedFieldOptions($field),
                'inline' => $field->get('inline'),
                'default' => $field->get('default'),
                'placeholder' => __($field->get('placeholder')),
                'autocomplete' => $field->get('autocomplete') ?? 'off',
                'width' => $field->get('width') ?? 100,
                'rules' => collect($field->rules())->flatten()->toArray(),
                'realtime' => $field->get('realtime'),
                'show_label' => $field->get('show_label') ?? true,
                'cast_booleans' => $field->get('cast_booleans') ?? false,
                'show' => $this->shouldShowField($field),
            ];
        });

        return $this;
    }

    protected function removeDuplicateCaptchaFields(): self
    {
        $duplicates = $this->fields->filter(function ($field) {
            return $field['type'] === 'captcha';
        })->slice(1);

        $this->fields = $this->fields->reject(function ($field) use ($duplicates) {
            return $duplicates->contains($field);
        });

        return $this;
    }

    protected function addHoneypotField(): self
    {
        $handle = $this->form->honeypot();

        $this->fields = $this->fields->merge([
            $handle => [
                'label' => Str::ucfirst($handle),
                'handle' => "{$this->id}_{$handle}",
                'key' => 'data.' . $handle,
                'type' => 'honeypot',
                'width' => 100,
                'rules' => [],
                'show' => false,
            ]
        ]);

        return $this;
    }

    protected function getTranslatedFieldOptions($field): array
    {
        return collect($field->get('options'))->map(function ($option) {
            return __($option);
        })->toArray();
    }

    protected function assignFieldType(string $type): string
    {
        $types = [
            'assets' => 'file',
            'captcha' => 'captcha',
            'checkboxes' => 'checkboxes',
            'integer' => 'input',
            'radio' => 'radios',
            'select' => 'select',
            'text' => 'input',
            'textarea' => 'textarea',
        ];

        return $types[$type] ?? 'input';
    }

    protected function assignFieldInputType(string $fieldType, ?string $intputType): ?string
    {
        $types = [
            'assets' => 'file',
            'checkboxes' => 'checkbox',
            'integer' => 'number',
            'radio' => 'radio',
        ];

        return $types[$fieldType] ?? $intputType;
    }

    protected function shouldShowField(Field $field): bool
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

    protected function getConditions(Field $field): array
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

    public function validationRules(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field['key'] => $field['rules']];
        })->toArray();
    }

    public function validationAttributes(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field['key'] => $field['label']];
        })->toArray();
    }

    public function realtimeValidationRules(string $field): array
    {
        $field = $this->fields->firstWhere('key', $field);

        // Don't use realtime validation for the honeypot.
        if ($field['type'] === 'honeypot') {
            return [$field['key'] => []];
        };

        // Don't use realtime validation for the captcha.
        if ($field['type'] === 'captcha') {
            return [$field['key'] => []];
        }

        // Get the realtime validation config from the field, form blueprint or global config.
        $realtime = $field['realtime']
            // Would like to get the realtime config from the form config instead of the form blueprint, but there's currently no way to access custom data.
            ?? $this->form->blueprint()->contents()['sections']['main']['realtime']
            ?? config('livewire-forms.realtime', true);

        // Disable realtime validation if "realtime: false".
        if (! $realtime) {
            return [$field['key'] => []];
        }

        // Use the field validation rules if "realtime: true".
        if ($realtime === true) {
            return [$field['key'] => $field['rules']];
        }

        // Make sure to always get an array of realtime rules.
        $realtimeRules = is_array($realtime) ? $realtime : explode('|', $realtime);

        // Remove any realtime rules that are not part of the validation rules.
        $realtimeRules = array_intersect($realtimeRules, $field['rules']);

        return [$field['key'] => $realtimeRules];
    }
}
