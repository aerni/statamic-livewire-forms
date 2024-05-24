<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Facades\Lang;

trait WithMessages
{
    public function successMessage(): string
    {
        if (Lang::has("livewire-forms.{$this->handle}.success_message")) {
            return __("livewire-forms.{$this->handle}.success_message");
        }

        if (Lang::has("livewire-forms::messages.{$this->handle}.success_message")) {
            return __("livewire-forms::messages.{$this->handle}.success_message");
        }

        return __('livewire-forms::messages.success_message');
    }

    public function errorMessage(): string
    {
        if (Lang::has("livewire-forms.{$this->handle}.error_message")) {
            return trans_choice("livewire-forms.{$this->handle}.error_message", $this->getErrorBag()->count());
        }

        if (Lang::has("livewire-forms::messages.{$this->handle}.error_message")) {
            return trans_choice("livewire-forms::messages.{$this->handle}.error_message", $this->getErrorBag()->count());
        }

        return trans_choice('livewire-forms::messages.error_message', $this->getErrorBag()->count());
    }

    public function submitButtonLabel(): string
    {
        if (Lang::has("livewire-forms.{$this->handle}.submit_button_label")) {
            return __("livewire-forms.{$this->handle}.submit_button_label");
        }

        if (Lang::has("livewire-forms::messages.{$this->handle}.submit_button_label")) {
            return __("livewire-forms::messages.{$this->handle}.submit_button_label");
        }

        return __('livewire-forms::messages.submit_button_label');
    }
}
