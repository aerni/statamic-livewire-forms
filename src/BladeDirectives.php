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
    public static function field(string $handle): string
    {
        return Blade::compileString("
            @if (isset(\$fields))
                @include('livewire-forms::field', ['field' => \$fields[$handle]])
            @endif
        ");
    }
}
