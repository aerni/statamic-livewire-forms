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

    public array $stepVisibility = [];

    #[Computed]
    public function steps(): Collection
    {
        /* Ensure the step status can be assigned correctly on subsequent requests. */
        unset($this->foundCurrentStep);

        return $this->formSections
            ->map(fn (Section $section, int $index) => new Step(
                number: $index + 1,
                status: StepStatus::Next,
                fields: $this->fields->intersectByKeys($section->fields()->all()),
                display: $section->display(),
                instructions: $section->instructions(),
            ))
            ->mapWithKeys(fn (Step $step) => [$step->number() => $this->assignStepStatus($step)]);
    }

    protected function assignStepStatus(Step $step): Step
    {
        $this->foundCurrentStep ??= false;

        if (! $this->stepIsVisible($step->handle())) {
            $step->status = StepStatus::Invisible;

            return $step;
        }

        $step->status = $this->foundCurrentStep
            ? StepStatus::Next
            : StepStatus::Previous;

        if ($step->number() === $this->currentStep) {
            $this->foundCurrentStep = true;
            $step->status = StepStatus::Current;
        }

        return $step;
    }

    protected function setCurrentStep(int $step): void
    {
        $this->currentStep = $step;

        unset($this->steps);
    }

    public function currentStep(): Step
    {
        $step = $this->steps->get($this->currentStep);

        throw_unless($step, StepDoesNotExist::stepNotFound($this->currentStep));

        throw_if($step->isInvisible(), StepDoesNotExist::stepIsInvisible($this->currentStep));

        return $step;
    }

    public function previousStep(): void
    {
        $previousStep = $this->steps->last(fn (Step $step) => $step->isPrevious());

        throw_unless($previousStep, StepDoesNotExist::noPreviousStep($this->currentStep));

        $this->setCurrentStep($previousStep->number());
    }

    public function nextStep(): void
    {
        $this->currentStep()->validate();

        $nextStep = $this->steps->first(fn (Step $step) => $step->isNext());

        throw_unless($nextStep, StepDoesNotExist::noNextStep($this->currentStep));

        $this->setCurrentStep($nextStep->number());
    }

    public function showStep(int $step): void
    {
        if ($step === $this->currentStep) {
            return;
        }

        /* Only validate if we are navigating forward. */
        if ($step > $this->currentStep) {
            $this->currentStep()->validate();
        }

        $this->setCurrentStep($step);
    }

    #[Computed]
    public function hasPreviousStep(): bool
    {
        return (bool) $this->steps->last(fn (Step $step) => $step->isPrevious());
    }

    #[Computed]
    public function hasNextStep(): bool
    {
        return (bool) $this->steps->first(fn (Step $step) => $step->isNext());
    }

    #[Computed]
    public function isLastStep(): bool
    {
        return ! $this->hasNextStep;
    }

    public function canNavigateToStep(int $step): bool
    {
        $step = $this->steps->get($step);

        /* Allow navigation to all previous steps. */
        if ($step->isPrevious()) {
            return true;
        }

        /* Only allow navigation to the immediate next step. */
        if ($step === $this->steps->first(fn (Step $step) => $step->isNext())) {
            return true;
        }

        return false;
    }

    protected function stepIsVisible(string $handle): bool
    {
        return $this->stepVisibility[$handle] ?? true;
    }

    public function updatedStepVisibility(bool $visible, string $key): void
    {
        /* Remove validation errors of hidden steps. */
        if (! $visible) {
            $this->steps->firstWhere(fn ($step) => $step->handle() === $key)->forgetErrors();
        }
    }
}
