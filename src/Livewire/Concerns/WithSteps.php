<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Aerni\LivewireForms\Form\Step;
use Aerni\LivewireForms\Form\Section;
use Aerni\LivewireForms\Enums\StepStatus;
use Aerni\LivewireForms\Exceptions\StepNotFoundException;
use Illuminate\Support\Collection;

trait WithSteps
{
    public Collection $steps;

    public int $currentStepNumber = 1;

    public function bootedWithSteps(): void
    {
        $this->steps = $this->steps();
    }

    public function steps(): Collection
    {
        $currentFound = false;
        $currentStepName = $this->currentStepNumber;

        return $this->sections
            ->map(function (Section $section)  use (&$currentFound, $currentStepName) {
                $status = $currentFound ? StepStatus::Next : StepStatus::Previous;

                if ($section->order() === $currentStepName) {
                    $currentFound = true;
                    $status = StepStatus::Current;
                }

                return new Step($section->order(), $status);
            });
    }

    #[Computed]
    public function currentStep()
    {
        return $this->steps->get($this->currentStepNumber - 1);
    }

    #[Computed]
    public function hasPreviousStep(): bool
    {
        return $this->steps
            ->filter(fn ($step) => $step->order() === $this->currentStepNumber - 1)
            ->isNotEmpty();
    }

    #[Computed]
    public function hasNextStep(): bool
    {
        return $this->steps
            ->filter(fn ($step) => $step->order() === $this->currentStepNumber + 1)
            ->isNotEmpty();
    }

    public function previousStep()
    {
        $previousStep = $this->steps
            ->before(fn (Step $step) => $step->order() === $this->currentStepNumber);

        if (! $previousStep) {
            throw new StepNotFoundException($this->currentStepNumber, $this->currentStepNumber - 1);
        }

        $this->toStep($previousStep->order());
    }

    public function nextStep()
    {
        $nextStep = $this->steps
            ->after(fn (Step $step) => $step->order() === $this->currentStepNumber);

        if (! $nextStep) {
            throw new StepNotFoundException($this->currentStepNumber, $this->currentStepNumber + 1);
        }

        $this->toStep($nextStep->order());
    }

    public function toStep(int $step)
    {
        $this->currentStepNumber = $step;
        $this->steps = $this->steps();
    }
}
