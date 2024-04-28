<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Livewire\Livewire;

trait WithComponent
{
    #[Computed(true)]
    public function component(): string
    {
        $component = str($this->handle)->replace('_', '-')->append('-form')->__toString();

        return Livewire::isDiscoverable($component)
            ? $component
            : "{$this->type}-form";
    }
}
