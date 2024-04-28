<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Facades\ViewManager;
use Aerni\LivewireForms\Livewire\Concerns\SubmitsForm;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BasicForm extends Component
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
        return view(ViewManager::viewPath($this->view));
    }
}
