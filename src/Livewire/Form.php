<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Livewire\Concerns\SubmitsForm;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Form extends Component
{
    use WithHandle;
    use WithTheme;
    use WithView;
    use WithFields;
    use WithForm;
    use WithMessages;
    use SubmitsForm;

    public function render(): View
    {
        return view("livewire-forms::{$this->view}");
    }
}
