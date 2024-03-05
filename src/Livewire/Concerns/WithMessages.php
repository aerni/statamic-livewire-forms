<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Facades\Lang;

trait WithMessages
{
    public function successMessage(): string
    {
        return Lang::has("livewire-forms.{$this->handle}.success_message")
            ? __("livewire-forms.{$this->handle}.success_message")
            : __('livewire-forms::messages.success_message');
    }

    public function errorMessage(): string
    {
        return Lang::has("livewire-forms.{$this->handle}.error_message")
            ? trans_choice("livewire-forms.{$this->handle}.error_message", $this->getErrorBag()->count())
            : trans_choice('livewire-forms::messages.error_message', $this->getErrorBag()->count());
    }

    public function submitButtonLabel(): string
    {
        return Lang::has("livewire-forms.{$this->handle}.submit_button_label")
            ? __("livewire-forms.{$this->handle}.submit_button_label")
            : __('livewire-forms::messages.submit_button_label');
    }
}
