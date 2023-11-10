<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Illuminate\Support\Facades\Blade;

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
     * Get a specific form view.
     */
    public static function formView(string $expression): string
    {
        $variables = explode(', ', $expression);

        $view = $variables[0];
        $arguments = $variables[1] ?? '[]';

        return Blade::compileString("
            @include(\$this->component->getView($view), $arguments)
        ");
    }

    /**
     * Get all the fields grouped by section
     */
    public static function formSections(): string
    {
        return Blade::compileString("
            @formView('layouts.sections')
        ");
    }

    /**
     * Get the fields of a specific section by its handle
     */
    public static function formSection(string $expression): string
    {
        return Blade::compileString("
            @formView('layouts.section', [
                'section' => \$this->fields->section($expression),
            ])
        ");
    }

    /**
     * Get a single form field by its handle.
     */
    public static function formField(string $expression): string
    {
        $variables = explode(', ', $expression);

        $field = $variables[0];
        $arguments = $variables[1] ?? '[]';

        return "<?php
            if (\$this->fields->get($field)) {
                echo \Aerni\LivewireForms\Facades\View::field(\$this->fields->get($field), $arguments);
            }
        ?>";
    }

    /**
     * Get the honeypot field.
     */
    public static function formHoneypot(): string
    {
        return Blade::compileString("
            @formView('fields.honeypot')
        ");
    }

    /**
     * Get the form submit button.
     */
    public static function formSubmit(): string
    {
        return Blade::compileString("
            @formView('layouts.submit')
        ");
    }

    /**
     * Get the form errors messages.
     */
    public static function formErrors(): string
    {
        return Blade::compileString("
            @formView('messages.errors')
        ");
    }

    /**
     * Get the form success message.
     */
    public static function formSuccess(): string
    {
        return Blade::compileString("
            @formView('messages.success')
        ");
    }
}
