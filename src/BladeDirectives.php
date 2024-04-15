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
        $variables = explode(', ', $expression, 2);

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
        return "<?php
            \$section = \$this->section($expression);

            if (! \$section) {
                throw new \InvalidArgumentException(\"The section $expression doesn't exist in the form's blueprint.\");
            }

            echo view(\Aerni\LivewireForms\Facades\ViewManager::themeViewPath(\$this->theme, 'layouts.section'), ['section' => \$section]);
        ?>";
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
            \$field = \$this->fields->get($field);

            if (! \$field) {
                throw new \InvalidArgumentException(\"The field $field doesn't exist in the form's blueprint.\");
            }

            foreach ($properties as \$property => \$value) {
                \$field->\$property(\$value);
            }

            echo view(\Aerni\LivewireForms\Facades\ViewManager::themeViewPath(\$this->theme, 'layouts.field'), ['field' => \$field]);
        ?>";
    }
}
