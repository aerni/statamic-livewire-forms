<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Contracts\Support\Arrayable;
use Statamic\Fields\Field as StatamicField;
use Statamic\Support\Traits\FluentlyGetsAndSets;
use Aerni\LivewireForms\Fields\Concerns\HandlesProperties;
use Aerni\LivewireForms\Fields\Concerns\WithDefaultProperties;

abstract class Field implements Arrayable
{
    use FluentlyGetsAndSets;
    use HandlesProperties;
    use WithDefaultProperties;

    public function __construct(
        protected StatamicField $field,
        protected string $id,
        protected mixed $value = null
    ) {
        //
    }

    public static function make(StatamicField $field, string $id): self
    {
        return new static($field, $id);
    }

    public function rules(): array
    {
        return [$this->key => $this->rules];
    }

    public function validationAttributes(): array
    {
        return [$this->key => $this->label];
    }

    public function process(): mixed
    {
        return $this->field->setValue($this->value)->process()->value();
    }

    public function value(mixed $value = null): mixed
    {
        return $this->fluentlyGetOrSet('value')
            ->getter(fn ($value) => $value ?? $this->default)
            ->args(func_get_args());
    }

    public function resetValue(): self
    {
        $this->value = $this->default;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'handle' => $this->field->handle(),
            'config' => $this->field->config(),
            'properties' => $this->properties(),
            'value' => $this->value(),
        ];
    }
}
