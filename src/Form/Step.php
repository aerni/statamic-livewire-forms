<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Enums\StepStatus;
use Aerni\LivewireForms\Fields\Field;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;

class Step
{
    public function __construct(
        public StepStatus $status,
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
        return Livewire::current()->fields->intersectByKeys(array_flip($this->fields));
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

    public function isInvisible(): bool
    {
        return $this->status === StepStatus::Invisible;
    }

    public function show(): string
    {
        return "showStep({$this->number})";
    }

    public function hasErrors(): bool
    {
        return Livewire::current()
            ->getErrorBag()
            ->hasAny($this->fields()->map->key()->all());
    }

    public function resetErrorBag(): void
    {
        Livewire::current()
            ->resetStepErrorBag($this->fields()->map->key()->values()->all());
    }

    public function validate(): void
    {
        $rules = $this->fields()
            ->mapWithKeys(fn (Field $field) => $field->rules())
            ->toArray();

        Livewire::current()->validate($rules);

        /*
        * The error bag is reset when the validation of a step passes.
        * This leads to error messages of other steps being reset as well.
        * To prevent this, we restore the previous error bag.
        */
        $this->resetErrorBag();
    }
}
