<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesSuccess
{
    protected function handleSuccess(): self
    {
        session()->flash('success', $this->successMessage());

        /* Get the captcha value before we are resetting the field values. */
        $captcha = $this->captcha()?->value();

        $this->resetValues();

        /* Preserve the captcha state by setting the value to its previous state. */
        $this->captcha()?->value($captcha);

        /* Dispatch event so we can reset the FilePond field. */
        $this->dispatch('form-success', id: $this->getId());

        return $this;
    }
}
