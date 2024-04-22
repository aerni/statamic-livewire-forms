<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Exceptions\HandleNotFoundException;
use Aerni\LivewireForms\Livewire\Form;
use Aerni\LivewireForms\Livewire\WizardForm;
use Livewire\Attributes\Locked;

trait WithHandle
{
    #[Locked]
    public string $handle;

    public function mountWithHandle(): void
    {
        $this->handle = $this->handle();
    }

    protected function handle(): string
    {
        // Get the handle passed to the component in the view.
        if (isset($this->handle)) {
            return $this->handle;
        }

        // Get the handle from the name of the component, e.g. 'contact-us-form' will load the 'contact_us' form.
        // TODO: Make sure this still works as expected.
        if ($this instanceof Form || $this instanceof WizardForm) {
            return str($this->getName())->beforeLast('-form')->replace('-', '_');
        }

        throw new HandleNotFoundException();
    }
}
