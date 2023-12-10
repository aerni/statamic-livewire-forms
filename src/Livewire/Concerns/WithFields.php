<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Form\Fields;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

trait WithFields
{
    use WithModels;

    #[Locked]
    public array $fieldsToSubmit = [];

    #[Computed]
    public function fields(): Fields
    {
        return Fields::make($this->form, $this->getId())
            ->models($this->models)
            ->hydrated(fn ($fields) => $this->hydratedFields($fields))
            ->hydrate();
    }

    protected function hydratedFields(Fields $fields): void
    {
        //
    }

    #[Renderless]
    #[On('field-conditions-updated')]
    public function submitFieldValue(string $field, bool $passesConditions): void
    {
        $this->fields->get($field)->always_save
            ? $this->fieldsToSubmit[$field] = true
            : $this->fieldsToSubmit[$field] = $passesConditions;
    }
}
