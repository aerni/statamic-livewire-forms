<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;

class BladeDirectives
{
    /**
     * Get the rendered captcha head view.
     */
    public static function captchaHead()
    {
        return Captcha::head();
    }

    /**
     * Get the captcha's key.
     */
    public static function captchaKey()
    {
        return Captcha::key();
    }
}
