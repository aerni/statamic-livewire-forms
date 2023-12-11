<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Captcha;
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

    protected function defaultValues(): Collection
    {
        return $this->fields->map(fn ($field) => $field->default());
    }

    protected function captchaValue(): array
    {
        return $this->fields
            ->whereInstanceOf(Captcha::class)
            ->all();
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
