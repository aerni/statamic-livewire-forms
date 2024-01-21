<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Facades\ViewManager;

trait WithViewManager
{
    public function mountWithViewManager(): void
    {
        ViewManager::boot($this);
    }

    public function hydrateWithViewManager(): void
    {
        ViewManager::boot($this);
    }
}
