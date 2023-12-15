<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesSuccess
{
    protected function handleSuccess(): self
    {
        session()->flash('success', $this->successMessage());

        // Get the captcha value before we are resetting the field values.
        $captcha = $this->captcha()?->value();

        $this->resetValues();

        // Preserve the captcha state by setting the value to its previous state.
        $this->captcha()?->value($captcha);

        return $this;
    }
}
