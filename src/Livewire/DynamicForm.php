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
    use WithComponent;
    use WithHandle;
    use WithTheme;
    use WithView;

    public function render(): View
    {
        return view('livewire-forms::dynamic-form');
    }
}
