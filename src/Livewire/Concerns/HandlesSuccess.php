<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesSuccess
{
    protected function handleSuccess(): self
    {
        // Flash the success message.
        session()->flash('success', $this->successMessage());

        // Reset the fields while preserving the captcha.
        $this->fields = $this->fields()
            ->merge($this->captchaValue());

        return $this;
    }
}
