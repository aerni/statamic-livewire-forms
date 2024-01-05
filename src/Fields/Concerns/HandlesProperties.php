<?php

namespace Aerni\LivewireForms\Fields\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionMethod;
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
                    ->mapWithKeys(fn ($property) => [$property => $this->property($property)])
                    ->all();
            })
            ->args(func_get_args());
    }

    public function property(string $key): mixed
    {
        $key = Str::snake($key);

        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }

        $method = $this->propertyMethodFromKey($key);

        $value = method_exists($this, $method)
            ? $this->$method()
            : $this->field->get($key);

        $this->properties[$key] = $value;

        return $value;
    }

    protected function propertyKeys(): Collection
    {
        $methodProperties = collect(get_class_methods($this))
            ->filter(fn ($method) => $this->isPropertyMethod($method))
            ->map(fn ($method) => $this->propertyKeyFromMethod($method));

        $configProperties = array_keys($this->field->config());

        return $methodProperties
            ->merge($configProperties)
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

    protected function propertyMethodFromKey(string $key): string
    {
        return Str::camel($key).'Property';
    }

    protected function get(string $key): mixed
    {
        return $this->property(Str::snake($key));
    }

    protected function set(string $key, mixed $value, bool $processValue = true): self
    {
        $key = Str::snake($key);

        $method = $this->propertyMethodFromKey($key);

        /**
         * If the property has a method that accepts an argument, we want to use it to transform the value.
         * This is useful for properties like `view` where we want the final value to be `fields.{view}`.
         */
        if ($processValue && method_exists($this, $method)) {
            $method = new ReflectionMethod($this, $method);

            $value = $method->getNumberOfParameters() > 0
                ? $method->invoke($this, $value)
                : $value;
        }

        $this->properties[$key] = $value;

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
            ? $this->set(key: $property, value: $arguments[0], processValue: $arguments[1] ?? true)
            : $this->get($property);
    }
}
