<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesSuccess
{
    protected function handleSuccess(): self
    {
        session()->flash('success', $this->successMessage());

        // Get the captcha value before we are resetting all field values.
        $captcha = $this->captchaValue();

        $this->resetValues();

        // Preserve the captcha state by setting it's value again.
        $this->captcha()?->value($captcha);

        return $this;
    }
}
