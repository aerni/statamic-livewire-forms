<?php

namespace Aerni\LivewireForms;

use Illuminate\Support\Facades\Blade;
use Aerni\LivewireForms\Facades\Captcha;

class BladeDirectives
{
    /**
     * Get the captcha's key.
     */
    public static function captchaKey(): string
    {
        return Captcha::key();
    }

    /**
     * Get all form fields.
     */
    public static function formFields(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('fields'))
        ");
    }

    /**
     * Get all fields of a group.
     */
    public static function formGroups(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('groups'))
        ");
    }

    /**
     * Get all fields of a group.
     */
    public static function formGroup(string $expression): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('group'), [
                'group' => $expression,
            ])
        ");
    }

    /**
     * Get a single form field by its handle.
     */
    public static function formField(string $expression): string
    {
        $variables = explode(', ', $expression);

        $handle = $variables[0];
        $rawField = isset($variables[1]) ? 'true' : 'false';

        return Blade::compileString("
            @if (\$this->fields->get($handle))
                @include(\$this->component->getView('field'), [
                    'field' => \$this->fields->get($handle),
                    'rawField' => $rawField,
                ])
            @endif
        ");
    }

    /**
     * Get the honeypot field.
     */
    public static function formHoneypot(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('honeypot'))
        ");
    }

    /**
     * Get the form submit button.
     */
    public static function formSubmit(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('submit'))
        ");
    }

    /**
     * Get the form errors messages.
     */
    public static function formErrors(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('errors'))
        ");
    }

    /**
     * Get the form success message.
     */
    public static function formSuccess(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('success'))
        ");
    }
}
