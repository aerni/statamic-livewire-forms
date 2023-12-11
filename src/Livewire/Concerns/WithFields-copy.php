<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Form\Fields;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

trait WithFieldsOld
{
    use WithModels;

    public Fields $synthFields;

    #[Locked]
    public array $fieldsToSubmit = [];

    public function mountWithFields()
    {
        $this->synthFields = Fields::make($this->form, $this->getId())
            ->models($this->models)
            ->hydrate();

        // $this->hydrateSynthFields($this->synthFields);
    }

    public function hydrateSynthFields(Fields $fields): void
    {
        //
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
        // This is set before the component is first rendered. Resulting in no flash.
        $this->set('first_name', 'John');
        // $fields->get('first_name')->value('John');

        // dd($fields);
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
