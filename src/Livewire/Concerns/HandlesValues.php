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
            ->map(fn ($field) => $this->submittableValue($field->value(), $this->submittableFields[$field->handle]));
    }

    protected function submittableValue(mixed $value, mixed $submittable): mixed
    {
        /* A non-group field has no children, so its value stays as it is. */
        if (! is_array($value) || ! is_array($submittable)) {
            return $value;
        }

        /* An included group lists each child. A missing child stays, and false drops it. */
        return collect($value)
            ->filter(fn ($child, $handle) => ($submittable[$handle] ?? true) !== false)
            ->map(fn ($child, $handle) => $this->submittableValue($child, $submittable[$handle] ?? true))
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
