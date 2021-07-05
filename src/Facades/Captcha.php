<?php

namespace Aerni\LivewireForms\Facades;

use Illuminate\Support\Facades\Facade;

class Captcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\LivewireForms\Captcha\ReCaptcha::class;
    }
}
