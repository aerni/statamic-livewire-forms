<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Livewire\Form;

trait WithHandle
{
    public string $handle;

    public function mountWithHandle(): void
    {
        $this->handle = $this->handle();
    }

    protected function handle(): string
    {
        // Try to get the handle defined in a custom component.
        if (isset(static::$HANDLE)) {
            return static::$HANDLE;
        }

        // Try to get the handle passed to the component in the view.
        if (isset($this->handle)) {
            return $this->handle;
        }

        // Get the handle from the name of the component, e.g. 'contact-us-form' will load the 'contact_us' form.
        if ($this instanceof Form) {
            return str($this->getName())->beforeLast('-form')->replace('-', '_');
        }

        throw new \Exception('You need to set the handle of the form you want to use.');
    }
}
