<?php

namespace Aerni\LivewireForms\Traits;

use Statamic\Fields\Field;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait GetsFormFields
{
    protected function fields(): Collection
    {
        return $this->form->fields()
            ->map(function ($field) {
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
                    'error' => $this->getFieldError('data.' . $field->handle()),
                    'show_label' => $field->get('show_label') ?? true,
                    'cast_booleans' => $field->get('cast_booleans') ?? false,
                    'show' => $this->shouldShowField($field),
                ];
            })->reject(function ($field, $handle) {
                return $this->duplicateCaptchaFields()->contains($handle);
            })->merge($this->honeypotField());
    }

    protected function captchaFields(): Collection
    {
        return $this->form->fields()->filter(function ($field) {
            return $field->type() === 'captcha';
        });
    }

    protected function duplicateCaptchaFields(): Collection
    {
        return $this->captchaFields()->slice(1)->keys();
    }

    protected function honeypotField(): array
    {
        $field = $this->form->honeypot();

        return [
            $field => [
                'label' => Str::ucfirst($field),
                'handle' => "{$this->id}_{$field}",
                'key' => 'data.' . $field,
                'type' => 'honeypot',
                'width' => 100,
                'rules' => [],
            ]
        ];
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

    protected function getFieldError(string $field): ?string
    {
        if (! $this->getErrorBag()->has($field)) {
            return null;
        }

        return $this->getErrorBag()->first($field);
    }

    protected function shouldShowField(Field $field): bool
    {
        $conditions = collect($field->get('if'));

        // Always show the field if there are no conditions.
        if ($conditions->isEmpty()) {
            return true;
        }

        // Determine if the field should be shown or not.
        $conditions = $conditions->map(function ($condition, $field) {
            [$operator, $actualValue] = explode(' ', $condition);

            $expectedValue = collect($this->data)->get($field);

            return $this->evaluateCondition($actualValue, $operator, $expectedValue);
        });

        return $conditions->count() === $conditions->filter()->count();
    }

    protected function evaluateCondition(string $actualValue, string $operator, string $expectedValue): bool
    {
        switch ($operator) {
            case "equals":
                return $actualValue == $expectedValue;
            case "not":
                return $actualValue != $expectedValue;
            case "===":
                return $actualValue === $expectedValue;
            case "!==":
                return $actualValue !== $expectedValue;
            case ">":
                return $actualValue > $expectedValue;
            case ">=":
                return $actualValue >= $expectedValue;
            case "<":
                return $actualValue <  $expectedValue;
            case "<=":
                return $actualValue <= $expectedValue;
            default:
                return false;
        }
    }
}
