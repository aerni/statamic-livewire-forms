<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Enums\StepStatus;
use Aerni\LivewireForms\Exceptions\StepDoesNotExist;
use Aerni\LivewireForms\Form\Step;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Statamic\Fields\Section;

trait WithSteps
{
    public int $currentStep = 1;

    #[Computed]
    public function steps(): Collection
    {
        $currentFound = false;

        return $this->formSections->mapWithKeys(function (Section $section, int $index) use (&$currentFound) {
            $number = $index + 1;
            $status = $currentFound ? StepStatus::Next : StepStatus::Previous;

            if ($number === $this->currentStep) {
                $currentFound = true;
                $status = StepStatus::Current;
            }

            return [$number => new Step(
                number: $number,
                status: $status,
                fields: $this->fields->intersectByKeys($section->fields()->all()),
                display: $section->display(),
                instructions: $section->instructions(),
            )];
        });
    }

    public function currentStep(): Step
    {
        return throw_unless(
            $this->steps->firstWhere(fn (Step $step) => $step->isCurrent()),
            StepDoesNotExist::stepNotFound($this->currentStep)
        );
    }

    public function previousStep(?int $step = null): void
    {
        $previousStep = $this->steps->get($step)
            ?? $this->steps->before(fn (Step $step) => $step->isCurrent());

        throw_unless($previousStep, StepDoesNotExist::noPreviousStep($this->currentStep));

        $this->currentStep = $previousStep->number;

        unset($this->steps);
    }

    public function nextStep(?int $step = null): void
    {
        if (! $this->currentStep()->validate()) {
            return;
        }

        $nextStep = $this->steps->get($step)
            ?? $this->steps->after(fn (Step $step) => $step->isCurrent());

        throw_unless($nextStep, StepDoesNotExist::noNextStep($this->currentStep));

        $this->currentStep = $nextStep->number;

        unset($this->steps);
    }

    public function showStep(int $step): void
    {
        throw_unless($this->steps->has($step), StepDoesNotExist::stepNotFound($step));

        match (true) {
            ($step > $this->currentStep) => $this->nextStep($step),
            ($step < $this->currentStep) => $this->previousStep($step),
            default => null
        };
    }

    #[Computed]
    public function hasPreviousStep(): bool
    {
        return (bool) $this->steps->before(fn (Step $step) => $step->isCurrent());
    }

    #[Computed]
    public function hasNextStep(): bool
    {
        return (bool) $this->steps->after(fn (Step $step) => $step->isCurrent());
    }

    #[Computed]
    public function isLastStep(): bool
    {
        return $this->steps->last()->isCurrent();
    }

    public function canNavigatetoStep(int $step): bool
    {
        return $step < $this->currentStep || $step === $this->currentStep + 1;
    }
}
