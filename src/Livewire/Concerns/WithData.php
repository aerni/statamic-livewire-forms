<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Collection;
use Livewire\WithFileUploads;

trait WithData
{
    use WithFileUploads;

    protected function values(): Collection
    {
        return $this->fields->map(fn ($field) => $field->value());
    }

    protected function processedValues(): Collection
    {
        return $this->fields->map(fn ($field) => $field->process());
    }

    protected function resetValues(): Collection
    {
        return $this->fields->each(fn ($field) => $field->resetValue());
    }

    protected function captchaValue(): ?string
    {
        return $this->captcha()?->value();
    }

    protected function get(string $key): mixed
    {
        return $this->fields->get($key)->value();
    }

    protected function set(string $key, mixed $value): self
    {
        $this->fields->get($key)->value($value);

        return $this;
    }
}
