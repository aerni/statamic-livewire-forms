<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Livewire\WizardForm;
use Livewire\Attributes\Computed;

trait HandlesSuccess
{
    protected function handleSuccess(): self
    {
        session()->flash('success', $this->successMessage());

        if ($this->resetFormOnSuccess) {
            $this->resetForm();
        }

        /* Dispatch event so we can reset the FilePond field. */
        $this->dispatch('form-success', id: $this->getId());

        return $this;
    }

    public function resetForm(): void
    {
        /* Get the captcha value before we are resetting the field values. */
        $captcha = $this->captcha()?->value();

        $this->resetValues();

        /* Preserve the captcha state by setting the value to its previous state. */
        $this->captcha()?->value($captcha);

        if ($this instanceof WizardForm) {
            $this->reset('currentStep');
        }
    }

    #[Computed]
    public function success(): bool
    {
        return session()->has('success');
    }
}
