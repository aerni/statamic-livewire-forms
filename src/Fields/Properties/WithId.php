<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Livewire\Livewire;

trait WithId
{
    protected function idProperty(): string
    {
        return Livewire::current()->getId() . '-field-' . $this->handle;
    }
}
