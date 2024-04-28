<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Locked;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Exceptions\HandleNotFoundException;

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
        if (in_array(WithForm::class, class_uses_recursive($this))) {
            return str($this->getName())->beforeLast('-form')->replace('-', '_');
        }

        throw new HandleNotFoundException();
    }
}
