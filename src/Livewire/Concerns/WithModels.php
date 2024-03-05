<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Collection;

trait WithModels
{
    protected array $models = [];

    public function models(): Collection
    {
        return collect(config('livewire-forms.models'))->merge($this->models);
    }
}
