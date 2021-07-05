<?php

namespace Aerni\LivewireForms\Traits;

trait WithCaptcha
{
    protected function withCaptcha(): bool
    {
        return $this->form->blueprint()->contents()['sections']['main']['captcha'] ?? false;
    }

    protected function captchaValidationMessages(): array
    {
        return [
            'captcha.required' => 'The reCAPTCHA field is required.',
            'captcha.captcha' => 'The reCAPTCHA challenge was not successful.',
        ];
    }
}
