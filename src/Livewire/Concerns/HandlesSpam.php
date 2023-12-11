<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Statamic\Exceptions\SilentFormFailureException;

trait HandlesSpam
{
    protected function handleSpam(): self
    {
        if ($this->isSpam()) {
            throw new SilentFormFailureException();
        }

        return $this;
    }

    protected function isSpam(): bool
    {
        return $this->fields->get($this->honeypot->handle)->value() !== null;
    }
}
