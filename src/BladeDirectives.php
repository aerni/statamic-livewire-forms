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
     * An alias of formSections
     */
    public static function formFields(): string
    {
        return static::formSections();
    }

    /**
     * Get all the fields grouped by section
     */
    public static function formSections(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('sections'))
        ");
    }

    /**
     * Get the fields of a specific section by its handle
     */
    public static function formSection(string $expression): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('section'), [
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
        $properties = $variables[1] ?? '[]';

        return "<?php
            if (\$this->fields->get($field)) {
                echo \Aerni\LivewireForms\Facades\View::field(\$this->fields->get($field), $properties);
            }
        ?>";
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
