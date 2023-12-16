<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Statamic\Exceptions\SilentFormFailureException;

trait HandlesSpam
{
    protected function handleSpam(): self
    {
        throw_if($this->isSpam(), new SilentFormFailureException);

        return $this;
    }

    protected function isSpam(): bool
    {
        return $this->fields->get($this->honeypot->handle)->value() !== null;
    }
}
