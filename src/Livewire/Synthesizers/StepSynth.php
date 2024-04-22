<?php

namespace Aerni\LivewireForms\Livewire\Synthesizers;

use Aerni\LivewireForms\Form\Step;
use Aerni\LivewireForms\Enums\StepStatus;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class StepSynth extends Synth
{
    public static $key = 'step';

    public static function match($target)
    {
        return $target instanceof Step;
    }

    public function dehydrate($target)
    {
        return [[
            'number' => $target->number,
            'status' => $target->status->value,
        ], []];
    }

    public function hydrate($value)
    {
        return new Step($value['number'], StepStatus::from($value['status']));
    }
}
