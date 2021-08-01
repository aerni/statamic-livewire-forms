<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;

class BladeDirectives
{
    /**
     * Get the rendered captcha scripts view.
     */
    public static function captchaScripts(): string
    {
        return '{!! \Aerni\LivewireForms\Facades\Captcha::scripts() !!}';
    }

    /**
     * Get the captcha's key.
     */
    public static function captchaKey(): string
    {
        return Captcha::key();
    }
}
