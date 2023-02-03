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
        // Hash the response to comply with memory limit of some cache drivers.
        $hashedResponse = md5($response);

        /**
         * The captcha's response can only be verified once.
         * If the user verify's the captcha but the validation of the form fails for some other reason
         * we need to return the cached response rather than trying to verify it again.
         */
        if (Cache::has("captcha:response:{$hashedResponse}")) {
            return $this->isValid($hashedResponse);
        }

        $verifiedResponse = Http::asForm()->post($this->verificationUrl(), [
            'secret' => $this->secret(),
            'response' => $response,
            'remoteip' => $clientIp,
        ])->json();

        Cache::put("captcha:response:{$hashedResponse}", $verifiedResponse);

        return $this->isValid($hashedResponse);
    }

    /**
     * Check if the verified response is valid.
     */
    protected function isValid(string $hashedResponse): bool
    {
        $verifiedResponse = Cache::get("captcha:response:{$hashedResponse}");

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
