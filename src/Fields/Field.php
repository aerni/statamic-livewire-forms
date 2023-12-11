<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAlwaysSave;
use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithHidden;
use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithInstructionsPosition;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithView;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithWireModel;
use Statamic\Fields\Field as StatamicField;
use Statamic\Support\Str;

abstract class Field
{
    use WithAlwaysSave;
    use WithConditions;
    use WithDefault;
    use WithHandle;
    use WithHidden;
    use WithId;
    use WithInstructions;
    use WithInstructionsPosition;
    use WithKey;
    use WithLabel;
    use WithRules;
    use WithShowLabel;
    use WithView;
    use WithWidth;
    use WithWireModel;

    protected mixed $value;

    public function __construct(protected StatamicField $field, protected string $id)
    {
        // TODO: Can we move this initialization somewhere else?
        $this->value = $this->default;
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

    public function process(mixed $value): mixed
    {
        if ($this->cast_booleans && in_array($value, ['true', 'false'])) {
            return Str::toBool($value);
        }

        return $value;
    }

    public function value(mixed $value = null): mixed
    {
        if (is_null($value)) {
            return $this->value;
        }

        $this->value = $value;

        return $this;
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
