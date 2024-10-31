<?php

namespace Aerni\LivewireForms\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithType;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithRedirect;
use Aerni\LivewireForms\Livewire\Concerns\WithComponent;

class DynamicForm extends Component
{
    use WithComponent;
    use WithForm;
    use WithHandle;
    use WithTheme;
    use WithType;
    use WithView;
    use WithRedirect;

    public function render(): View
    {
        return view('livewire-forms::dynamic-form');
    }
}
