<?php

namespace Aerni\LivewireForms\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Aerni\LivewireForms\Facades\ViewManager;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\SubmitsForm;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;
use Aerni\LivewireForms\Livewire\Concerns\WithSteps;

class WizardForm extends Component
{
    use WithHandle;
    use WithTheme;
    use WithView;
    use WithFields;
    use WithForm;
    use WithMessages;
    use WithSteps;
    use SubmitsForm;

    public function render(): View
    {
        return view(ViewManager::viewPath($this->view), [
            'step' => $this->currentStep(),
        ]);
    }
}
