<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;

class Section
{
    public function __construct(
        protected int $number,
        protected array $fields,
        protected ?string $display,
        protected ?string $instructions,
    ) {}

    public function number(): int
    {
        return $this->number;
    }

    public function handle(): string
    {
        return Str::snake($this->display ?? $this->number);
    }

    public function id(): string
    {
        return Livewire::current()->getId().'-section-'.$this->number;
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
        return Livewire::current()->fields->intersectByKeys(array_flip($this->fields));
    }
}
