<?php

namespace Aerni\LivewireForms\Fields;

use ReflectionClass;
use ReflectionMethod;
use Statamic\Support\Str;
use Statamic\Fields\Field as StatamicField;
use Aerni\LivewireForms\Traits\WithConfig;
use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithType;
use Aerni\LivewireForms\Fields\Properties\WithGroup;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithRealtime;
use Aerni\LivewireForms\Fields\Properties\WithConditions;

class Field
{
    use WithConfig,
        WithConditions,
        WithDefault,
        WithGroup,
        WithHandle,
        WithId,
        WithKey,
        WithLabel,
        WithRealtime,
        WithRules,
        WithType,
        WithWidth;

    public function __construct(protected StatamicField $field, protected string $id)
    {
        //
    }

    public static function make(StatamicField $field, string $id): self
    {
        return (new static($field, $id))->boot();
    }

    protected function boot(): self
    {
        $properties = $this->getProperties(get_class($this));

        return $this->config($properties);
    }

    protected function getProperties(string $class): array
    {
        return collect((new ReflectionClass($class))->getMethods())
            ->mapWithKeys(function ($method) {
                if (! Str::contains($method->name, 'Property')) {
                    return [];
                }

                $key = Str::snake(Str::replace($method->name, 'Property', ''));
                $value = (new ReflectionMethod($this, $method->name))->invoke($this);

                return [$key => $value];
            })->toArray();
    }
}