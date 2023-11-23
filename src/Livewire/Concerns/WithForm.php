<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Statamic\Facades\Form;
use Livewire\Attributes\Computed;

trait WithForm
{
    #[Computed(true)]
    public function form(): \Statamic\Forms\Form
    {
        return Form::find($this->handle)
            ?? throw new \Exception("Form with handle [{$this->handle}] cannot be found.");
    }
}
