<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithGroup;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithRealtime;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithShow;
use Aerni\LivewireForms\Fields\Properties\WithView;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithWireModelModifier;
use Aerni\LivewireForms\Traits\WithConfig;
use ReflectionClass;
use ReflectionMethod;
use Statamic\Fields\Field as StatamicField;
use Statamic\Support\Str;

abstract class Field
{
    use WithConditions;
    use WithConfig;
    use WithDefault;
    use WithGroup;
    use WithHandle;
    use WithId;
    use WithKey;
    use WithLabel;
    use WithRealtime;
    use WithRules;
    use WithShow;
    use WithView;
    use WithWidth;
    use WithWireModelModifier;

    public function __construct(protected StatamicField $field, protected string $id)
    {
        //
    }

    public static function make(StatamicField $field, string $id): self
    {
        return (new static($field, $id))->hydrate();
    }

    public function hydrate(): self
    {
        $properties = $this->hydrateProperties(get_class($this));

        return $this->config($properties);
    }

    protected function hydrateProperties(string $class): array
    {
        return collect((new ReflectionClass($class))->getMethods())
            ->mapWithKeys(function ($method) {
                if (! Str::contains($method->name, 'Property')) {
                    return [];
                }

                $key = Str::snake(Str::replace($method->name, 'Property', ''));
                $value = (new ReflectionMethod($this, $method->name))->invoke($this);

                return [$key => $value];
            })->sortKeys()->toArray();
    }
}
