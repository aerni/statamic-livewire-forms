<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesValues
{
    use WithFileUploads;

    public array $submittableFields = [];

    protected function resetValues(): void
    {
        $this->fields->each(fn ($field) => $field->resetValue());
    }

    protected function get(string $key): mixed
    {
        return $this->fields->get($key)->value();
    }

    protected function set(string $key, mixed $value): self
    {
        $this->fields->get($key)->value($value);

        return $this;
    }
}
