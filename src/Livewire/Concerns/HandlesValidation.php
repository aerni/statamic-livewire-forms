<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesValidation
{
    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    protected function rules(): array
    {
        return $this->fields->validationRules();
    }

    protected function validationAttributes(): array
    {
        return $this->fields->validationAttributes();
    }
}
