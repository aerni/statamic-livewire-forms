<?php

namespace Aerni\LivewireForms\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Contracts\View\View;
use Aerni\LivewireForms\Livewire\Concerns\WithData;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Statamic\Exceptions\SilentFormFailureException;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithModels;
use Aerni\LivewireForms\Livewire\Concerns\HandlesSpam;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;
use Aerni\LivewireForms\Livewire\Concerns\HandlesSuccess;
use Aerni\LivewireForms\Livewire\Concerns\HandlesSubmission;
use Aerni\LivewireForms\Livewire\Concerns\HandlesValidation;

class Form extends Component
{
    use HandlesSpam;
    use HandlesSubmission;
    use HandlesSuccess;
    use HandlesValidation;
    use WithData;
    use WithFields;
    use WithFileUploads;
    use WithForm;
    use WithHandle;
    use WithModels;
    use WithTheme;
    use WithView;
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
