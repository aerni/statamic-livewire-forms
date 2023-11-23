<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Livewire;
use Illuminate\Support\Str;

trait WithComponent
{
    public string $component;

    public function mountWithComponent(): void
    {
        $this->component = $this->component();
    }

    protected function component(): string
    {
        $component = Str::replace('_', '-', $this->handle).'-form';

        return Livewire::isDiscoverable($component) ? $component : 'default-form';
    }
}
