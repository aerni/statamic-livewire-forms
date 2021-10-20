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
     * Get a single field by its handle.
     */
    public static function formfield(string $expression): string
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
}
