<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithView
{
    public function viewProperty(): string
    {
        return match ($this->field->type()) {
            'assets' => 'livewire-forms::fields.file',
            'captcha' => 'livewire-forms::fields.captcha',
            'checkboxes' => 'livewire-forms::fields.checkboxes',
            'integer' => 'livewire-forms::fields.input',
            'radio' => 'livewire-forms::fields.radios',
            'select' => 'livewire-forms::fields.select',
            'text' => 'livewire-forms::fields.input',
            'textarea' => 'livewire-forms::fields.textarea',
            default => 'livewire-forms::fields.input',
        };
    }
}
