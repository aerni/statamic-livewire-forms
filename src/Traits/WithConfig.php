<?php

namespace Aerni\LivewireForms\Traits;

use Illuminate\Support\Arr;

trait WithConfig
{
    protected array $config = [];

    public function config(?array $config = null): array|self
    {
        if (! $config) {
            return $this->config;
        }

        $this->config = $config;

        return $this;
    }

    public function merge(array $config): self
    {
        array_merge($this->config, $config);

        return $this;
    }

    public function get(string $key): mixed
    {
        return Arr::get($this->config, $key);
    }

    public function set(string $key, mixed $value): self
    {
        $this->config[$key] = $value;

        return $this;
    }

    public function __get(string $key): mixed
    {
        return Arr::get($this->config, $key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (! $arguments) {
            return Arr::get($this->config, $name);
        }

        $this->config[$name] = $arguments[0];

        return $this;
    }
}
