<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Concerns\HandlesProperties;
use Aerni\LivewireForms\Fields\Concerns\WithDefaultProperties;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Statamic\Fields\Field as FormField;
use Statamic\Support\Traits\FluentlyGetsAndSets;

abstract class Field implements Arrayable
{
    use FluentlyGetsAndSets;
    use HandlesProperties;
    use WithDefaultProperties;

    protected mixed $value = null;

    public function __construct(protected FormField $field)
    {
        //
    }

    public static function make(FormField $field): self
    {
        return new static($field);
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

    public function section(): ?string
    {
        return Livewire::current()->sections()
            ->firstWhere(fn ($section) => $section->fields()->has($this->handle))
            ?->handle(); /* The honeypot field doesn't have a section, which is why this will return */
    }

    public function toArray(): array
    {
        return [
            'handle' => $this->field->handle(),
            'section' => $this->section(),
            'config' => $this->field->config(),
            'properties' => $this->properties(),
            'value' => $this->value(),
        ];
    }
}
