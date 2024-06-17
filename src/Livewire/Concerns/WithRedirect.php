<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Locked;

trait WithRedirect
{
    #[Locked]
    public string $redirect = '';
}
