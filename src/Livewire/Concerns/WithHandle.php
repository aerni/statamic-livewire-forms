<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait WithHandle
{
    public string $handle;

    public function mountWithHandle(): void
    {
        $this->handle = $this->handle();
    }

    protected function handle(): string
    {
        return static::$HANDLE // Try to get the handle defined in a custom component.
            ?? $this->handle // Try to get the handle passed to the component in the view.
            ?? throw new \Exception('You need to set the handle of the form you want to use.');
    }
}
