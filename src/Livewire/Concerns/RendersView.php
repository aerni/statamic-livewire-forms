<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Contracts\View\View;

trait RendersView
{
    public function render(): View
    {
        return view("livewire-forms::{$this->view}");
    }
}
