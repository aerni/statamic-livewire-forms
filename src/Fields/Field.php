<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithGroup;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
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
    use WithInstructions;
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

    public function id(): string
    {
        return "{$this->id}_{$this->handle()}";
    }

    public function handle(): string
    {
        return $this->field->handle();
    }

    public function key(): string
    {
        return "data.{$this->handle()}";
    }

    protected function get(string $key): mixed
    {
        $property = collect((new ReflectionClass($this))->getMethods())
            ->first(fn ($method) => Str::startsWith($method->name, Str::camel($key)))
            ?->invoke($this);

        return $property ?? $this->field->get(Str::snake($key));
    }

    protected function set(string $key, mixed $value): self
    {
        $newConfig = collect($this->field->config())->put(Str::snake($key), $value)->all();

        $this->field->setConfig($newConfig);

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
