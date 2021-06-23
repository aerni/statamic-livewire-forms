<?php

namespace Aerni\LivewireForms\Captcha;

use Aerni\LivewireForms\Captcha\BaseCaptcha;

class ReCaptcha extends BaseCaptcha
{
    public function getResponseToken()
    {
        return request('g-recaptcha-response');
    }

    public function getVerificationUrl()
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }

    public function getDefaultDisclaimer()
    {
        return 'This site is protected by reCAPTCHA and the Google [Privacy Policy](https://policies.google.com/privacy) and [Terms of Service](https://policies.google.com/terms) apply.';
    }

    public function renderIndexTag()
    {
        $attributes = $this->buildAttributes([
            'data-sitekey' => $this->getSiteKey(),
            'data-size' => config('livewire-forms.captcha.invisible') ? 'invisible' : '',
        ]);

        return "<div class=\"g-recaptcha\" {$attributes}></div>";
    }

    public function renderHeadTag()
    {
        return view('livewire-forms::recaptcha.head', [
            'invisible' => config('livewire-forms.captcha.invisible'),
            'hide_badge' => config('livewire-forms.captcha.hide_badge'),
        ])->render();
    }
}
