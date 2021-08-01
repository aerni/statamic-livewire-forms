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
        /**
         * There is an open issue preventing the Antlers parser from dynamically rendering the scripts section.
         * Until this issue is resolved, we have to load the scripts even if they are not needed on a particular view.
         * See: https://github.com/statamic/cms/issues/3286
         */
        return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';

        // TODO: Use this when the section issue is resolved.
        // return CaptchaFacade::scripts();
    }
}
