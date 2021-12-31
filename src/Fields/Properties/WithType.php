<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithType
{
    public function typeProperty(): string
    {
        return match ($this->field->type()) {
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
}
