<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Form\Fields;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

trait WithFields
{
    // TODO: Can we make this protected?
    public Collection $fieldsToSubmit;

    public function mountWithFields(): void
    {
        $this->fieldsToSubmit = collect();
    }

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
            ? $this->fieldsToSubmit->put($field, true)
            : $this->fieldsToSubmit->put($field, $passesConditions);
    }
}
