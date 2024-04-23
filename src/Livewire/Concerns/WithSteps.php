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

        return $this->form->blueprint()->tabs()->first()->sections()
            ->filter(fn (Section $section) => $section->fields()->all()->isNotEmpty())
            ->values()
            ->mapWithKeys(function (Section $section, int $index) use (&$currentFound) {
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
        return $this->steps->firstWhere(fn (Step $step) => $step->isCurrent());
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

    public function showStep(int $step): void
    {
        if ($step === $this->currentStep) {
            return;
        }

        throw_unless($this->steps->has($step), StepDoesNotExist::stepNotFound($step));

        $this->currentStep = $step;

        unset($this->steps);
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
}
