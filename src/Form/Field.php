<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Arr;

class Field
{
    public function __construct(protected array $config)
    {
        //
    }

    public function __get(string $name): mixed
    {
        return Arr::get($this->config, $name);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->config[$name] = $value;
    }

    public function config(array $config = null): array|self
    {
        if (! $config) {
            return $this->config;
        }

        $this->config = $config;

        return $this;
    }
}
