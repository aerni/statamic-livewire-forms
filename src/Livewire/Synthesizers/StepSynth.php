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

    public function dehydrate($target, $dehydrateChild)
    {
        $data = $target->toArray();

        foreach ($data as $key => $child) {
            $data[$key] = $dehydrateChild($key, $child);
        }

        return [
            $data,
            ['class' => get_class($target)],
        ];
    }

    public function hydrate($value, $meta, $hydrateChild)
    {
        foreach ($value as $key => $child) {
            $value[$key] = $hydrateChild($key, $child);
        }

        return new Step(
            number: $value['number'],
            fields: $value['fields'],
            display: $value['display'],
            instructions: $value['instructions'],
            status: StepStatus::from($value['status']),
        );
    }
}
