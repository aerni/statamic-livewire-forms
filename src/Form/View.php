<?php

namespace Aerni\LivewireForms\Form;

class View
{
    const DEFAULT_THEME = 'livewire-forms';

    public function __construct(protected string $theme)
    {
        //
    }

    public static function make(string $theme): self
    {
        return new static($theme);
    }

    public function get(string $view): string
    {
        return $this->theme === self::DEFAULT_THEME
            ? self::DEFAULT_THEME . '::' . $view
            : "livewire.forms.{$this->theme}.{$view}";
    }
}
