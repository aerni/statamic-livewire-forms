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

    /**
     * Get the captcha's key.
     */
    public function key(): string
    {
        return CaptchaFacade::key();
    }

    /**
     * Get the unique ID of this captcha.
     */
    public function id(): string
    {
        return $this->context->get('_instance')->id;
    }
}
