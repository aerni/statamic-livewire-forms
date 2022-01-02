<?php

namespace Aerni\LivewireForms\Form;

class Theme
{
    public static function root(string $theme = null): string
    {
        return $theme
            ? "livewire.forms.$theme."
            : 'livewire-forms::';
    }
}
