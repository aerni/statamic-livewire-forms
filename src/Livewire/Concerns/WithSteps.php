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

    public int $currentStep = 1;

    public function mountWithSteps(): void
    {
        $this->steps = $this->steps();
    }

    public function steps(): Collection
    {
        $currentFound = false;
        $currentStepName = $this->currentStep;

        return $this->sections
            ->mapWithKeys(function (Section $section)  use (&$currentFound, $currentStepName) {
                $status = $currentFound ? StepStatus::Next : StepStatus::Previous;

                if ($section->order() === $currentStepName) {
                    $currentFound = true;
                    $status = StepStatus::Current;
                }

                return [$section->order() => new Step($section->order(), $status)];
            });
    }

    public function currentStep(): Step
    {
        return $this->steps->get($this->currentStep);
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

    public function previousStep(): void
    {
        $previousStep = $this->steps->before(fn (Step $step) => $step->isCurrent());

        throw_unless($previousStep, StepDoesNotExist::noPreviousStep($this->currentStep));

        $this->showStep($previousStep->number);
    }

    public function nextStep(): void
    {
        $nextStep = $this->steps->after(fn (Step $step) => $step->isCurrent());

        throw_unless($nextStep, StepDoesNotExist::noNextStep($this->currentStep));

        $this->showStep($nextStep->number);
    }

    public function showStep(int $step)
    {
        $this->currentStep = $step;
        $this->steps = $this->steps();
    }
}
