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
            @include('livewire-forms::fields')
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
            @if (isset(\$fields[$handle]))
                @include('livewire-forms::field', [
                    'field' => \$fields[$handle],
                    'rawField' => $rawField,
                ])
            @endif
        ");
    }

    /**
     * Get the form submit button.
     */
    public static function formSubmit(): string
    {
        return Blade::compileString("
            @include('livewire-forms::submit')
        ");
    }

    /**
     * Get the form errors messages.
     */
    public static function formErrors(): string
    {
        return Blade::compileString("
            @include('livewire-forms::errors')
        ");
    }

    /**
     * Get the form success message.
     */
    public static function formSuccess(): string
    {
        return Blade::compileString("
            @include('livewire-forms::success')
        ");
    }
}
