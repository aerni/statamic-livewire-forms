<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Facades\ViewManager;
use Aerni\LivewireForms\Livewire\Concerns\SubmitsForm;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;
use Aerni\LivewireForms\Livewire\Concerns\WithSections;
use Aerni\LivewireForms\Livewire\Concerns\WithSteps;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithType;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BaseForm extends Component
{
    use WithHandle;
    use WithTheme;
    use WithType;
    use WithView;
    use WithForm;
    use WithFields;
    use WithSections;
    use WithSteps;
    use WithMessages;
    use SubmitsForm;

    public function render(): View
    {
        return view(ViewManager::viewPath($this->view), [
            'step' => $this->currentStep(),
        ]);
    }
}
