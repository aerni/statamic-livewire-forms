<?php

namespace Aerni\LivewireForms\Fields\Concerns;

use Aerni\LivewireForms\Exceptions\ReadOnlyPropertyException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Support\Traits\FluentlyGetsAndSets;

trait HandlesProperties
{
    use FluentlyGetsAndSets;

    protected array $properties = [];

    public function properties(?array $properties = null): array|self
    {
        return $this->fluentlyGetOrSet('properties')
            ->getter(function () {
                return $this->propertyKeys()
                    ->mapWithKeys(fn ($property) => [$property => $this->get($property)])
                    ->all();
            })
            ->args(func_get_args());
    }

    protected function propertyKeys(): Collection
    {
        $methodProperties = collect(get_class_methods($this))
            ->filter(fn ($method) => $this->isPropertyMethod($method))
            ->map(fn ($method) => $this->propertyKeyFromMethod($method));

        $configProperties = array_keys($this->field->config());

        $existingProperties = array_keys($this->properties);

        return $methodProperties
            ->merge($configProperties)
            ->merge($existingProperties)
            ->unique();
    }

    protected function isPropertyMethod(string $method): bool
    {
        return Str::endsWith($method, 'Property');
    }

    protected function propertyKeyFromMethod(string $method): string
    {
        return Str::of($method)->beforeLast('Property')->snake();
    }

    protected function propertyMethodNameFromKey(string $key): string
    {
        return Str::camel($key).'Property';
    }

    protected function get(string $key): mixed
    {
        $key = Str::snake($key);

        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }

        $method = $this->propertyMethodNameFromKey($key);

        $value = method_exists($this, $method)
            ? $this->$method()
            : $this->field->get($key);

        $this->properties[$key] = $value;

        return $value;
    }

    protected function set(string $key, mixed $value): self
    {
        $key = Str::snake($key);

        $method = $this->propertyMethodNameFromKey($key);

        /* Allow setting arbitary properties and setting properties to null. */
        if (! method_exists($this, $method) || is_null($value)) {
            $this->properties[$key] = $value;

            return $this;
        }

        $method = new \ReflectionMethod($this, $method);

        /*
         * Process properties according to their methods.
         * Properties are considered read-only if their method doesn't accept an argument.
         * This is useful for properties like 'handle' or 'key' that shouldn't be changed.
         */
        $this->properties[$key] = $method->getNumberOfParameters() > 0
            ? $method->invoke($this, $value)
            : throw new ReadOnlyPropertyException($key);

        return $this;
    }

    public function unset(string|array $properties): self
    {
        $this->properties = collect($this->properties)
            ->forget($properties)
            ->all();

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
            ? $this->set(key: $property, value: $arguments[0])
            : $this->get($property);
    }
}
