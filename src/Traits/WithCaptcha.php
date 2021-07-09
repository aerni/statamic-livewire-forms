<?php

namespace Aerni\LivewireForms\Traits;

trait WithCaptcha
{
    protected function withCaptcha(): bool
    {
        return $this->form->blueprint()->contents()['sections']['main']['captcha']
            ?? config('livewire-forms.captcha.enabled', false);
    }

    protected function captchaValidationMessages(): array
    {
        return [
            'captcha.required' => __('The reCAPTCHA field is required.'),
            'captcha.captcha' => __('The reCAPTCHA challenge was not successful.'),
        ];
    }
}
