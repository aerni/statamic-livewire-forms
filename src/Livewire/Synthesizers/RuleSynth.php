<?php

namespace Aerni\LivewireForms\Livewire\Synthesizers;

use Illuminate\Contracts\Validation\Rule;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class RuleSynth extends Synth
{
    public static $key = 'rule';

    public static function match($target)
    {
        return $target instanceof Rule;
    }

    public function dehydrate($target)
    {
        return [
            invade($target)->parameters,
            ['class' => get_class($target)],
        ];
    }

    public function hydrate($value, $meta)
    {
        return new $meta['class']($value);
    }
}
