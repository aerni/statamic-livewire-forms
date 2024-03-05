<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Livewire;

trait WithComponent
{
    #[Computed(true)]
    public function component(): string
    {
        $component = Str::replace('_', '-', $this->handle).'-form';

        return Livewire::isDiscoverable($component) ? $component : 'default-form';
    }
}
