<?php

namespace Aerni\LivewireForms\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

trait GetsFormFields
{
    protected function fields(): Collection
    {
        return $this->form->fields()
            ->map(function ($field) {
                return [
                    'label' => $this->assignFieldLabel($field),
                    'instructions' => $field->get('instructions'),
                    'handle' => $field->handle(),
                    'key' => 'data.' . $field->handle(),
                    'type' => $this->assignFieldType($field->get('type')),
                    'input_type' => $this->assignFieldInputType($field->get('type'), $field->get('input_type')),
                    'options' => $field->get('options'),
                    'inline' => $field->get('inline'),
                    'default' => $field->get('default'),
                    'placeholder' => $field->get('placeholder'),
                    'autocomplete' => $field->get('autocomplete'),
                    'width' => $field->get('width') ?? 100,
                    'rules' => collect($field->rules())->flatten()->toArray(),
                    'realtime' => $field->get('realtime'),
                    'error' => $this->getFieldError('data.' . $field->handle()),
                    'show_label' => $field->get('show_label') ?? true,
                ];
            });
    }

    protected function honeypot(): array
    {
        return [
            'label' => Str::ucfirst($this->form->honeypot()),
            'handle' => $this->form->honeypot(),
            'key' => 'data.' . $this->form->honeypot(),
        ];
    }

    protected function assignFieldLabel($field): string
    {
        $formTranslation = "forms.{$this->formHandle}.{$field->handle()}";
        $defaultTranslation = "forms.default.{$field->handle()}";

        // Get label from specific form translations
        if (Lang::has($formTranslation)) {
            return Lang::get($formTranslation);
        };

        // Get label form default translations
        if (Lang::has($defaultTranslation)) {
            return Lang::get($defaultTranslation);
        };

        // Fallback to field display
        return $field->get('display');
    }

    protected function assignFieldType(string $type): string
    {
        $types = [
            'assets' => 'file',
            'checkboxes' => 'checkboxes',
            'integer' => 'input',
            'radio' => 'radio',
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
}
