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
    // TODO: Do we really need a synth or can we just use a computed property?
    public Collection $steps;

    public function mountWithSteps(): void
    {
        $this->steps = $this->steps();
    }

    protected function steps(): Collection
    {
        return $this->form->blueprint()->tabs()->first()->sections()
            ->filter(fn ($section) => $section->fields()->all()->isNotEmpty())
            ->values()
            ->mapWithKeys(function ($section, $index) {
                $number =  $index + 1;

                return [$number => new Step(
                    number: $number,
                    status: $number === 1 ? StepStatus::Current : StepStatus::Next,
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
