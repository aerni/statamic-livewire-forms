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
            @include(\Aerni\LivewireForms\Facades\ViewManager::themeViewPath(\$this->theme, $view), $arguments)
        ");
    }

    /**
     * Get the fields of a specific section by its handle
     */
    public static function formSection(string $expression): string
    {
        return Blade::compileString("
            @formView('layouts.section', [
                'section' => \$this->section($expression),
            ])
        ");
    }

    /**
     * Get a single form field by its handle.
     */
    public static function formField(string $expression): string
    {
        $variables = explode(', ', $expression, 2);

        $field = $variables[0];
        $properties = $variables[1] ?? '[]';

        return "<?php
            if (\$field = \$this->fields->get($field)) {
                foreach ($properties as \$property => \$value) {
                    \$field->\$property(\$value);
                }

                echo view(\Aerni\LivewireForms\Facades\ViewManager::themeViewPath(\$this->theme, 'layouts.field'), ['field' => \$field]);
            }
        ?>";
    }
}
