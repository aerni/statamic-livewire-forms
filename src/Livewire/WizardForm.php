<?php

namespace Aerni\LivewireForms\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Aerni\LivewireForms\Facades\ViewManager;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithType;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Aerni\LivewireForms\Livewire\Concerns\WithSteps;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\SubmitsForm;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;

class WizardForm extends Component
{
    use WithHandle;
    use WithTheme;
    use WithView;
    use WithFields;
    use WithForm;
    use WithType;
    use WithMessages;
    use WithSteps;
    use SubmitsForm;

    protected bool $resetFormOnSuccess = false;

    public function render(): View
    {
        return view(ViewManager::viewPath($this->view), [
            'step' => $this->currentStep(),
        ]);
    }
}
