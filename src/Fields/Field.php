<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Concerns\HandlesProperties;
use Aerni\LivewireForms\Fields\Concerns\WithDefaultProperties;
use Illuminate\Contracts\Support\Arrayable;
use Livewire\Component;
use Statamic\Fields\Field as FormField;
use Statamic\Support\Traits\FluentlyGetsAndSets;

abstract class Field implements Arrayable
{
    use FluentlyGetsAndSets;
    use HandlesProperties;
    use WithDefaultProperties;

    protected mixed $value = null;

    public function __construct(
        protected FormField $field,
        protected Component $component,
    ) {
    }

    public static function make(FormField $field, Component $component): self
    {
        return new static($field, $component);
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
