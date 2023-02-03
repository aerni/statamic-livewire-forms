<?php

namespace Aerni\LivewireForms\Captcha;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
        $hash = md5($response);
        if (Cache::has("captcha:response:{$hash}")) {
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
    protected function isValid(string $response): bool
    {
        $hash = md5($response);
        $verifiedResponse = Cache::get("captcha:response:{$hash}");

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
    protected function secret(): string
    {
        return config('livewire-forms.captcha.secret');
    }

    /**
     * Get the URL that's used to verify the captcha.
     */
    protected function verificationUrl(): string
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }
}
