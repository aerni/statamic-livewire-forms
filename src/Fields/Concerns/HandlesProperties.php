<?php

namespace Aerni\LivewireForms\Fields\Concerns;

use Illuminate\Support\Str;
use Statamic\Support\Traits\FluentlyGetsAndSets;

trait HandlesProperties
{
    use FluentlyGetsAndSets;

    protected array $properties = [];

    public function properties(?array $properties = null): array|self
    {
        return $this->fluentlyGetOrSet('properties')
            ->getter(function ($properties) {
                return empty($properties)
                    ? $this->processProperties()->properties()
                    : $properties;
            })
            ->args(func_get_args());
    }

    protected function property(string $key): mixed
    {
        return $this->properties[$key] ?? null;
    }

    protected function processProperties(): self
    {
        $this->properties = $this->field->toArray();

        collect(get_class_methods($this))
            ->filter(fn ($method) => $this->isPropertyMethod($method))
            ->each(fn ($method) => $this->processProperty($this->propertyKeyFromMethod($method)));

        return $this;
    }

    protected function processProperty(string $key): self
    {
        $method = $this->propertyMethodFromKey($key);

        $value = method_exists($this, $method)
            ? $this->$method()
            : $this->field->get($key);

        if (! is_null($value)) {
            $this->properties[$key] = $value;
        }

        return $this;
    }

    protected function isPropertyMethod(string $method): bool
    {
        return Str::endsWith($method, 'Property') && $method !== 'processProperty';
    }

    protected function propertyKeyFromMethod(string $method): string
    {
        return Str::of($method)->beforeLast('Property')->snake()->toString();
    }

    protected function propertyMethodFromKey(string $key): string
    {
        return Str::camel($key).'Property';
    }

    protected function get(string $key): mixed
    {
        $key = Str::snake($key);

        return $this->property($key)
            ?? $this->processProperty($key)->property($key);
    }

    protected function set(string $key, mixed $value): self
    {
        $this->properties[Str::snake($key)] = $value;

        return $this;
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    public function __call(string $property, array $arguments): mixed
    {
        return $arguments
            ? $this->set($property, $arguments[0])
            : $this->get($property);
    }
}
