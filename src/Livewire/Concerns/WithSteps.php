<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Aerni\LivewireForms\Form\Step;
use Aerni\LivewireForms\Form\Section;
use Aerni\LivewireForms\Enums\StepStatus;
use Aerni\LivewireForms\Exceptions\StepDoesNotExist;
use Illuminate\Support\Collection;

trait WithSteps
{
    public Collection $steps;

    public function mountWithSteps(): void
    {
        $this->steps = $this->steps();
    }

    protected function steps(): Collection
    {
        return $this->sections
            ->mapWithKeys(function (Section $section) {
                $status = $section->order() === 1
                    ? StepStatus::Current : StepStatus::Next;

                return [$section->order() => new Step($section->order(), $status)];
        });
    }

    public function currentStep(): Step
    {
        return $this->steps->firstWhere(fn (Step $step) => $step->isCurrent());
    }

    public function previousStep(): void
    {
        $previousStep = $this->steps->before(fn (Step $step) => $step->isCurrent());

        throw_unless($previousStep, StepDoesNotExist::noPreviousStep($this->currentStep()->number));

        $this->showStep($previousStep->number);
    }

    public function nextStep(): void
    {
        $nextStep = $this->steps->after(fn (Step $step) => $step->isCurrent());

        throw_unless($nextStep, StepDoesNotExist::noNextStep($this->currentStep()->number));

        $this->showStep($nextStep->number);
    }

    public function showStep(int $stepNumber): void
    {
        $step = $this->steps->get($stepNumber);

        throw_unless($step, StepDoesNotExist::stepNotFound($step->number));

        if ($step->isCurrent()) {
            return;
        }

        $step->status = StepStatus::Current;

        $this->steps
            ->filter(fn (Step $step) => $step->number < $stepNumber)
            ->each(fn (Step $step) => $step->status = StepStatus::Previous);

        $this->steps
            ->filter(fn (Step $step) => $step->number > $stepNumber)
            ->each(fn (Step $step) => $step->status = StepStatus::Next);
    }

    #[Computed]
    public function hasPreviousStep(): bool
    {
        return (bool) $this->steps->before(fn ($step) => $step->isCurrent());
    }

    #[Computed]
    public function hasNextStep(): bool
    {
        return (bool) $this->steps->after(fn ($step) => $step->isCurrent());
    }
}
