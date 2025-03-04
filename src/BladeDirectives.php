<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Aerni\LivewireForms\Fields\Assets;
use Aerni\LivewireForms\Fields\Captcha as CaptchaField;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

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

    /**
     * Push the Livewire Form assets into the head.
     */
    public static function formAssets(): string
    {
        $styles = collect();
        $scripts = collect(['/vendor/livewire-forms/js/form.js']);

        $fields = Livewire::current()->fields;

        if ($fields->contains(fn ($field) => $field instanceof Assets)) {
            $styles->push('/vendor/livewire-forms/css/filepond.css');
            $scripts->push('/vendor/livewire-forms/js/filepond.js');
        }

        if ($fields->contains(fn ($field) => $field instanceof CaptchaField)) {
            $scripts->push('/vendor/livewire-forms/js/grecaptcha.js');
        }

        $styles = $styles->map(fn ($style) => "<link href='{$style}' rel='stylesheet'/>")->implode("\n");
        $scripts = $scripts->map(fn ($script) => "<script src='{$script}' type='module'></script>")->implode("\n");

        return Blade::compileString("
            @assets
                $styles
                $scripts
            @endassets
        ");
    }
}
