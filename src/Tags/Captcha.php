<?php

namespace Aerni\LivewireForms\Tags;

use Statamic\Tags\Tags;
use Aerni\LivewireForms\Facades\Captcha as CaptchaFacade;

class Captcha extends Tags
{
    /**
     * Get the rendered captcha head view.
     */
    public function head(): string
    {
        return CaptchaFacade::head();
    }
}
