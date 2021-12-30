<?php

namespace Aerni\LivewireForms\Fields;

use ReflectionClass;
use ReflectionMethod;
use Statamic\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Statamic\Fields\Field as StatamicField;
use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithType;
use Aerni\LivewireForms\Fields\Properties\WithGroup;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithRealtime;
use Aerni\LivewireForms\Fields\Properties\WithConditions;

class Field
{
    use WithConditions,
        WithDefault,
        WithGroup,
        WithHandle,
        WithId,
        WithKey,
        WithRealtime,
        WithRules,
        WithType,
        WithWidth;

    protected array $config = [];

    public function __construct(protected StatamicField $field)
    {
        //
    }

    public static function make(StatamicField $field): self
    {
        return (new static($field))->process();
    }

    protected function process(): self
    {
        $baseProperties = $this->getPropertiesFromTraitMethods(get_parent_class($this));
        $classProperties = $this->getPropertiesFromTraitMethods(get_class($this));

        $allProperties = $baseProperties->merge($classProperties)->toArray();

        return $this->config($allProperties);
    }

    protected function getPropertiesFromTraitMethods(string $class): Collection
    {
        return collect((new ReflectionClass($class))->getTraits())->flatMap(function ($trait) {
            return collect($trait->getMethods())->mapWithKeys(function ($method) {
                if (! $method->isPublic()) {
                    return [];
                };

                $key = Str::snake($method->name);
                $value = (new ReflectionMethod($this, $method->name))->invoke($this);

                return [$key => $value];
            });
        });
    }

    public function config(array $config = null): array|self
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

    public function __set(string $key, mixed $value)
    {
        $this->config[$key] = $value;
    }
}
