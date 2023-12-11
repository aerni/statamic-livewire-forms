<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Locked;

trait WithModels
{
    #[Locked]
    public array $models = [];
}
