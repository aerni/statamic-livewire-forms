<?php

namespace Aerni\LivewireForms\Tags;

use Statamic\Tags\Tags;
use Statamic\Support\Html;
use Aerni\LivewireForms\Facades\Captcha as CaptchaFacade;

class Captcha extends Tags
{
    protected static $handle = 'captcha';

    /**
     * The {{ captcha }} tag
     *
     * @return string
     */
    public function index()
    {
        return CaptchaFacade::renderIndexTag();
    }

    /**
     * The {{ captcha:head }} tag
     *
     * @return string
     */
    public function head()
    {
        return CaptchaFacade::renderHeadTag();
    }

    /**
     * The {{ captcha:disclaimer }} tag
     *
     * @return string
     */
    public function disclaimer()
    {
        // TODO: Get disclaimer from lang file instead of config.
        // The disclaimer is needed if the captcha is invisible: https://github.com/aryehraber/statamic-captcha#invisible-captcha
        if (! $disclaimer = config('livewire-forms.captcha.disclaimer')) {
            $disclaimer = CaptchaFacade::getDefaultDisclaimer();
        }

        return Html::markdown($disclaimer);
    }

    /**
     * The {{ captcha:sitekey }} tag
     *
     * @return string
     */
    // TODO: Don't know what this is used for.
    // public function sitekey()
    // {
    //     return CaptchaFacade::getSiteKey();
    // }
}
