<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait HandlesSuccess
{
    protected function handleSuccess(): self
    {
        // Flash the success message.
        session()->flash('success', $this->successMessage());

        // Reset the form data.
        $this->data = $this->defaultData();

        // Reset asset fields using this trick: https://talltips.novate.co.uk/livewire/livewire-file-uploads-using-s3#removing-filename-from-input-field-after-upload
        $this->fields->getByType('assets')
            ->each(fn ($field) => $field->id($field->id().'_'.rand()));

        return $this;
    }
}
