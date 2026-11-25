<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Collection;

trait HandlesValues
{
    use WithFileUploads;

    public array $submittableFields = [];

    protected function submittableValues(): Collection
    {
        return $this->fields
            ->filter(fn ($field) => $this->submittableFields[$field->handle])
            ->map(fn ($field) => $this->submittableValue($field->value(), $field->handle));
    }

    protected function submittableValue(mixed $value, string $path): mixed
    {
        /* A non-group field has no children, so its value stays as it is. */
        if (! is_array($value)) {
            return $value;
        }

        /* A nested field's flag key is the dotted path, like address.street. */
        return collect($value)
            ->filter(fn ($child, $handle) => $this->submittableFields["{$path}.{$handle}"] ?? true)
            ->map(fn ($child, $handle) => $this->submittableValue($child, "{$path}.{$handle}"))
            ->all();
    }

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
