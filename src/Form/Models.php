<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Collection;

class Models
{
    protected Collection $models;

    public function __construct()
    {
        $this->models = collect(config('livewire-forms.models', []));
    }

    public function all(): Collection
    {
        return $this->models;
    }

    public function get(string $key): ?string
    {
        return $this->models->get($key);
    }

    public function register(array $models): void
    {
        $this->models = $this->models->merge($models);
    }
}
