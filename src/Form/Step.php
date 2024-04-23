<?php

namespace Aerni\LivewireForms\Form;

use Livewire\Livewire;
use Aerni\LivewireForms\Enums\StepStatus;

class Step
{
    public function __construct(
        public int $number,
        public StepStatus $status,
    ) {
    }

    public function id(): string
    {
        return $this->number . '-step';
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

    public function section(): Section
    {
        return Livewire::current()->sections->firstWhere(fn (Section $section) => $section->order () === $this->number);
    }

    public function __call($name, $arguments)
    {
        return $this->section()->$name($arguments);
    }
}
