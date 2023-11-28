<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Honeypot;
use Livewire\Attributes\Computed;
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
        $isSpam = $this->data()->has($this->honeypot->handle);

        if ($isSpam) {
            throw new SilentFormFailureException();
        }

        return $this;
    }
}
