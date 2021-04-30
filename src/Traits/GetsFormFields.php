<?php

namespace Aerni\StatamicLivewireForms\Traits;

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
                    'handle' => $field->handle(),
                    'key' => 'data.' . $field->handle(),
                    'type' => $this->assignFieldType($field->get('type')),
                    'input_type' => $this->assignFieldInputType($field->get('type'), $field->get('input_type')),
                    'default' => $field->get('default'),
                    'placeholder' => $field->get('placeholder'),
                    'autocomplete' => $field->get('autocomplete'),
                    'width' => $field->get('width') ?? 100,
                    'rules' => collect($field->rules())->flatten()->toArray(),
                    'realtime' => $field->get('realtime'),
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
        if (Lang::has('statamic-livewire-forms::forms.' . $field->handle())) {
            return Lang::get('statamic-livewire-forms::forms.' . $field->handle());
        };

        return $field->get('display');
    }

    protected function assignFieldType(string $type): string
    {
        $types = [
            'text' => 'input',
            'textarea' => 'textarea',
            'integer' => 'input',
            'checkboxes' => 'checkbox',
            'radio' => 'radio',
            'select' => 'select',
            'assets' => 'file',
        ];

        return $types[$type] ?? 'input';
    }

    protected function assignFieldInputType(string $fieldType, ?string $intputType): ?string
    {
        $types = [
            'integer' => 'number',
        ];

        return $types[$fieldType] ?? $intputType;
    }
}
