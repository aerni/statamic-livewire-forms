<?php

namespace Aerni\LivewireForms\Form;

class Models
{
    protected array $models;

    public function __construct()
    {
        $this->models = config('livewire-forms.models', []);
    }

    public function all(): array
    {
        return $this->models;
    }

    public function register(array $models): void
    {
        $this->models = array_merge($this->models, $models);
    }
}
