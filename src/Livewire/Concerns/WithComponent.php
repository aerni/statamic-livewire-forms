<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Livewire;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

trait WithComponent
{
    #[Computed(true)]
    public function component(): string
    {
        $component = Str::replace('_', '-', $this->handle).'-form';

        return Livewire::isDiscoverable($component)
            ? $component
            : "{$this->type}-form";
    }
}
