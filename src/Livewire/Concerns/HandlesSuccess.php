<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;

trait HandlesSuccess
{
    protected function handleSuccess(): self|null
    {
        session()->flash('success', $this->successMessage());
        session()->flash('submission', $this->submission);

        if (! empty($this->redirect)) {
            return $this->redirect($this->redirect);
        }

        if (! $this->isWizardForm()) {
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

        /* Reset the field values to the initial state. */
        $this->resetValues();

        /* Preserve the captcha state by setting the value to its previous state. */
        $this->captcha()?->value($captcha);

        /* Reset the wizard steps to the initial state. */
        $this->reset('currentStep');
    }

    #[Computed]
    public function success(): bool
    {
        return session()->has('success');
    }
}
