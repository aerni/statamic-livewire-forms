<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Enums\StepStatus;
use Aerni\LivewireForms\Exceptions\StepDoesNotExist;
use Aerni\LivewireForms\Form\Step;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Statamic\Fields\Section;

trait WithSteps
{
    public int $currentStep = 1;

    public array $stepVisibility = [];

    #[Computed]
    public function steps(): Collection
    {
        return $this->formSections
            ->map(fn (Section $section, int $index) => new Step(
                number: $index + 1,
                status: StepStatus::Next,
                fields: $this->fields->intersectByKeys($section->fields()->all()),
                display: $section->display(),
                instructions: $section->instructions(),
            ))
            ->mapWithKeys(fn (Step $step) => [$step->number => $this->assignStepStatus($step)]);
    }

    protected function assignStepStatus(Step $step): Step
    {
        $this->currentFound ??= false;

        if (! $this->stepIsVisible($step->handle())) {
            $step->status = StepStatus::Invisible;

            return $step;
        }

        $step->status = $this->currentFound
            ? StepStatus::Next
            : StepStatus::Previous;

        if ($step->number === $this->currentStep) {
            $this->currentFound = true;
            $step->status = StepStatus::Current;
        }

        return $step;
    }

    protected function step(int $number): Step
    {
        $step = $this->steps->get($number);

        throw_unless($step, StepDoesNotExist::stepNotFound($number));

        throw_if($step->isInvisible(), StepDoesNotExist::stepIsInvisible($number));

        return $step;
    }

    public function currentStep(): Step
    {
        return $this->step($this->currentStep);
    }

    public function previousStep(): void
    {
        $previousStep = $this->steps->last(fn (Step $step) => $step->isPrevious());

        throw_unless($previousStep, StepDoesNotExist::noPreviousStep($this->currentStep));

        $this->currentStep = $previousStep->number;

        unset($this->steps);
    }

    public function nextStep(): void
    {
        if (! $this->currentStep()->validate()) {
            return;
        }

        $nextStep = $this->steps->first(fn (Step $step) => $step->isNext());

        throw_unless($nextStep, StepDoesNotExist::noNextStep($this->currentStep));

        $this->currentStep = $nextStep->number;

        unset($this->steps);
    }

    public function showStep(int $number): void
    {
        $step = $this->step($number);

        /* Only validate if we are navigating forward */
        if ($step->number > $this->currentStep && ! $this->currentStep()->validate()) {
            return;
        }

        $this->currentStep = $step->number;

        unset($this->steps);
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

    public function canNavigateToStep(int $number): bool
    {
        $step = $this->steps->get($number);

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

    #[On('trigger-mutation')]
    public function triggerMutation(): void
    {
        /**
         * We need this method to immediately trigger an update of the $stepVisibility array
         * after the conditions have been evaluated on the frontend.
         */
    }
}
