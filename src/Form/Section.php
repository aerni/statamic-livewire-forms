<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;

class Section
{
    public function __construct(
        protected Collection $fields,
        protected int $order,
        protected ?string $display,
        protected ?string $instructions,
    ) {
    }

    public function handle(): string
    {
        return Str::snake($this->display ?? $this->order);
    }

    public function id(): string
    {
        return Livewire::current()->getId().'-section-'.$this->order;
    }

    public function display(): ?string
    {
        return __($this->display);
    }

    public function instructions(): ?string
    {
        return __($this->instructions);
    }

    public function fields(): Collection
    {
        return $this->fields;
    }

    public function order(): int
    {
        return $this->order;
    }
}
