<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Enums\StepStatus;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;

class Step implements Arrayable
{
    public function __construct(
        public int $number,
        public StepStatus $status,
        protected Collection $fields,
        protected ?string $display,
        protected ?string $instructions,
    ) {
    }

    public function handle(): string
    {
        return Str::snake($this->display ?? $this->number);
    }

    public function id(): string
    {
        return Livewire::current()->getId().'-step-'.$this->number;
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

    public function number(): int
    {
        return $this->number;
    }

    public function isPrevious(): bool
    {
        return $this->status === StepStatus::Previous;
    }

    public function isCurrent(): bool
    {
        return $this->status === StepStatus::Current;
    }

    public function isNext(): bool
    {
        return $this->status === StepStatus::Next;
    }

    public function show(): string
    {
        return "showStep({$this->number})";
    }

    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'fields' => $this->fields,
            'status' => $this->status->value,
            'display' => $this->display,
            'instructions' => $this->instructions,
        ];
    }
}
