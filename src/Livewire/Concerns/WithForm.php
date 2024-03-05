<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Exceptions\FormNotFoundException;
use Livewire\Attributes\Computed;
use Statamic\Facades\Form;

trait WithForm
{
    #[Computed(true)]
    public function form(): \Statamic\Forms\Form
    {
        return Form::find($this->handle)
            ?? throw new FormNotFoundException($this->handle);
    }
}
