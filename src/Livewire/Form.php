<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Livewire\Concerns\HandlesSpam;
use Aerni\LivewireForms\Livewire\Concerns\HandlesSubmission;
use Aerni\LivewireForms\Livewire\Concerns\HandlesSuccess;
use Aerni\LivewireForms\Livewire\Concerns\HandlesValidation;
use Aerni\LivewireForms\Livewire\Concerns\WithData;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;
use Aerni\LivewireForms\Livewire\Concerns\WithModels;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Statamic\Exceptions\SilentFormFailureException;

class Form extends Component
{
    use WithHandle;
    use WithView;
    use WithTheme;
    use WithForm;
    use WithModels;
    use WithFields;
    use WithData;
    use HandlesValidation;
    use HandlesSpam;
    use HandlesSubmission;
    use HandlesSuccess;
    use WithFileUploads;
    use WithMessages;

    public function render(): View
    {
        return view("livewire-forms::{$this->view}");
    }

    public function submit(): void
    {
        $this->validate();

        try {
            $this->handleSpam()->handleSubmission()->handleSuccess();
        } catch (SilentFormFailureException) {
            $this->handleSuccess();
        }
    }
}
