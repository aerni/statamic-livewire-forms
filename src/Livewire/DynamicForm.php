<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Livewire\Concerns\WithComponent;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DynamicForm extends Component
{
    use WithHandle;
    use WithComponent;
    use WithView;
    use WithTheme;

    public function render(): View
    {
        return view('livewire-forms::dynamic-form');
    }
}
