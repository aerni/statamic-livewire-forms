<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Aerni\LivewireForms\Form\Fields;

trait WithFields
{
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
}
