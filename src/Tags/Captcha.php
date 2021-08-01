<?php

namespace Aerni\LivewireForms\Tags;

use Statamic\Tags\Tags;
use Aerni\LivewireForms\Facades\Captcha as CaptchaFacade;

class Captcha extends Tags
{
    /**
     * Get the rendered captcha scripts view.
     */
    public function scripts(): string
    {
        return CaptchaFacade::scripts();
    }
}
