<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
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
use Statamic\Fields\Field as StatamicField;
use Statamic\Support\Str;

abstract class Field
{
    use WithConditions;
    use WithDefault;
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

    public function rules(): array
    {
        return [$this->key => $this->rules];
    }

    public function validationAttributes(): array
    {
        return [$this->key => $this->label];
    }

    protected function get(string $key): mixed
    {
        $method = collect(get_class_methods($this))
            ->first(fn ($method) => $method === Str::camel($key).'Property');

        return $method
            ? $this->$method()
            : $this->field->get(Str::snake($key));
    }

    protected function set(string $key, mixed $value): self
    {
        $newConfig = collect($this->field->config())
            ->put(Str::snake($key), $value)
            ->all();

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
