<?php

namespace Aerni\LivewireForms\Captcha;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class ReCaptcha
{
    /**
     * Verify the captcha's response.
     */
    public function verifyResponse(string $response, string $clientIp): bool
    {
        /**
         * The captcha's response can only be verified once.
         * If the user verify's the captcha but the validation of the form fails for some other reason
         * we need to return the cached response rather than trying to verify it again.
         */
        if (Cache::has("captcha:response:{$response}")) {
            return $this->isValid($response);
        }

        $verifiedResponse = Http::asForm()->post($this->verificationUrl(), [
            'secret' => $this->secret(),
            'response' => $response,
            'remoteip' => $clientIp,
        ])->json();

        Cache::put("captcha:response:{$response}", $verifiedResponse);

        return $this->isValid($response);
    }

    /**
    * Check if the verified response is valid.
    */
    public function isValid(string $response): bool
    {
        $verifiedResponse = Cache::get("captcha:response:{$response}");

        if (is_null($verifiedResponse)) {
            return false;
        }

        if ($verifiedResponse['success'] !== true) {
            return false;
        }

        return true;
    }

    /**
     * Get the captcha's site key.
     */
    public function key(): string
    {
        return config('livewire-forms.captcha.key');
    }

    /**
     * Get the captcha's secret key.
     */
    public function secret(): string
    {
        return config('livewire-forms.captcha.secret');
    }

    /**
     * Get the URL that's used to verify the captcha.
     */
    public function verificationUrl(): string
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }
}
