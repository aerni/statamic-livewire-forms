<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithGroup;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithRealtime;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithShow;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithView;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithWireModelModifier;
use ReflectionClass;
use Statamic\Fields\Field as StatamicField;
use Statamic\Support\Str;

abstract class Field
{
    use WithConditions;
    use WithDefault;
    use WithGroup;
    use WithHandle;
    use WithId;
    use WithInstructions;
    use WithKey;
    use WithLabel;
    use WithRealtime;
    use WithRules;
    use WithShow;
    use WithShowLabel;
    use WithView;
    use WithWidth;
    use WithWireModelModifier;

    public function __construct(protected StatamicField $field, protected string $id)
    {
        //
    }

    public static function make(StatamicField $field, string $id): self
    {
        return new static($field, $id);
    }

    public function field(): StatamicField
    {
        return $this->field;
    }

    public function __get(string $key): mixed
    {
        return collect((new ReflectionClass($this))->getMethods())
            ->first(fn ($method) => $method->name === Str::camel("{$key}Property"))
            ?->invoke($this);
    }

    public function __set(string $key, mixed $value): void
    {
        $newConfig = collect($this->field->config())->put($key, $value)->all();

        $this->field->setConfig($newConfig);
    }

    public function __call(string $method, array $arguments): mixed
    {
        if (! $arguments) {
            return $this->$method;
        }

        $this->$method = $arguments[0];

        return $this;
    }
}
