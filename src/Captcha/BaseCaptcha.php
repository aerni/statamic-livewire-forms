<?php

namespace Aerni\LivewireForms\Captcha;

use GuzzleHttp\Client;

// TODO: Add a contract.
abstract class BaseCaptcha
{
    protected $client;

    protected $data;

    /**
     * The cached verified responses.
     *
     * @var array
     */
    protected $verifiedResponses = [];

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    abstract public function getResponseToken();

    abstract public function getVerificationUrl();

    abstract public function getDefaultDisclaimer();

    abstract public function renderIndexTag();

    abstract public function renderHeadTag();

    public function verify($response): self
    {
        if (empty($response)) {
            return false;
        }

        // Return true if response already verfied before.
        if (in_array($response, $this->verifiedResponses)) {
            return true;
        }

        $query = [
            'secret' => $this->getSecret(),
            'response' => $this->getResponseToken(),
            'remoteip' => request()->ip(),
        ];

        $response = $this->client->post($this->getVerificationUrl(), compact('query'));

        if ($response->getStatusCode() == 200) {
            $this->data = collect(json_decode($response->getBody(), true));
        }

        ray($this);

        return $this;
    }

    // public function verify(): self
    // {
    //     $query = [
    //         'secret' => $this->getSecret(),
    //         'response' => $this->getResponseToken(),
    //         'remoteip' => request()->ip(),
    //     ];

    //     $response = $this->client->post($this->getVerificationUrl(), compact('query'));

    //     if ($response->getStatusCode() == 200) {
    //         $this->data = collect(json_decode($response->getBody(), true));
    //     }

    //     return $this;
    // }

    /**
     * Check whether the response was valid
     *
     * @return bool
     */
    public function validResponse(): bool
    {
        if (is_null($this->data)) {
            return false;
        }

        if (! $this->data->get('success')) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the response was invalid
     *
     * @return bool
     */
    public function invalidResponse(): bool
    {
        return ! $this->validResponse();
    }

    /**
     * Get the configured Captcha Site Key
     *
     * @return string
     */
    public function getSiteKey(): string
    {
        return config('livewire-forms.captcha.key');
    }

    /**
     * Get the configured Captcha Secret
     *
     * @return string
     */
    public function getSecret(): string
    {
        return config('livewire-forms.captcha.secret');
    }

    /**
     * Get the current domain, excluding 'http(s)://'
     *
     * @return string
     */
    // TODO: Check what this is used for.
    protected function currentDomain(): string
    {
        return preg_split('/http(s)?:\/\//', url())[1];
    }

    /**
     * Helper to build HTML element attributes string
     *
     * @return string
     */
    protected function buildAttributes($attributes): string
    {
        return collect($attributes)->filter()->map(function ($value, $key) {
            return sprintf('%s="%s"', $key, $value);
        })->implode(' ');
    }
}
