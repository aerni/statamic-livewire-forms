<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Statamic\Facades\Entry;

trait WithRedirect
{
    #[Locked]
    public string $redirect;

    public function mountWithRedirect(): void
    {
        $this->redirect ??= $this->form->redirect ?? '';

        /* This is a workaround because the form config fields are not augmented. So we have to manually get the entry. */
        if (Str::startsWith($this->redirect, 'entry::')) {
            $this->redirect = Entry::find(Str::after($this->redirect, 'entry::'))?->permalink ?? '';
        }
    }
}
