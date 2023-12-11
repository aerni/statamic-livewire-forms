<?php

namespace Aerni\LivewireForms\Livewire\Synthesizers;

use Aerni\LivewireForms\Form\Fields;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Statamic\Facades\Form;

class FieldsSynth extends Synth
{
    public static $key = 'fields';

    public static function match($target)
    {
        return $target instanceof Fields;
    }

    public function dehydrate($target)
    {
        return [
            [
                'handle' => $this->context->component->handle,
                'id' => $this->context->component->getId(),
                'models' => $this->context->component->models,
                'values' => $target->values(),
            ],
            [],
        ];
    }

    public function hydrate($value)
    {
        return Fields::make(Form::find($value['handle']), $value['id'])
            ->models($value['models'])
            ->hydrate()
            ->values($value['values']);
    }

    public function get(&$target, $key)
    {
        return $target->values();
    }

    public function set(&$target, $key, $value)
    {
        $target->values($value);
    }
}
