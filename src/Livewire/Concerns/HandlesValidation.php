<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesValidation
{
    public function updatedFields(string $field): void
    {
        $this->validateOnly($field);
    }

    protected function rules(): array
    {
        return $this->fields
            ->mapWithKeys(fn ($field) => $field->rules())
            ->toArray();
    }

    protected function validationAttributes(): array
    {
        return $this->fields
            ->mapWithKeys(fn ($field) => $field->validationAttributes())
            ->toArray();
    }
}
