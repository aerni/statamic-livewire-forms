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

    public function hasErrors(): bool
    {
        return Livewire::current()
            ->getErrorBag()
            ->hasAny($this->fields->map->key()->all());
    }

    public function validate(): bool
    {
        /**
         * The error bag is reset when the validation of the current step passes.
         * This leads to error messages of other steps being reset as well.
         * To prevent this, we can just return early if the current step has errors.
         * The fields of the step are still being validated with validateOnly() in the updatedFields() method.
         */
        if ($this->hasErrors()) {
            return false;
        }

        $errorBag = Livewire::current()->getErrorBag();

        $rules = $this->fields()
            ->mapWithKeys(fn (Field $field) => $field->rules())
            ->toArray();

        Livewire::current()->validate($rules);

        /*
        * The error bag is reset when the current step is validated.
        * This leads to error messages of other steps being reset as well.
        * To prevent this, we restore the previous error bag after the validation.
        */
        Livewire::current()->setErrorBag($errorBag);

        return true;
    }
}
