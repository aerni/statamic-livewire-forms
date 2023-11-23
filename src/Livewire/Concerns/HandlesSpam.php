<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Aerni\LivewireForms\Fields\Honeypot;
use Statamic\Exceptions\SilentFormFailureException;

trait HandlesSpam
{
    #[Computed(true)]
    public function honeypot(): Honeypot
    {
        return $this->fields->honeypot();
    }

    protected function handleSpam(): self
    {
        $isSpam = collect($this->data)->has($this->honeypot->handle);

        if ($isSpam) {
            throw new SilentFormFailureException();
        }

        return $this;
    }
}
