<?php

namespace Aerni\LivewireForms\Livewire\Synthesizers;

use Aerni\LivewireForms\Fields\Field;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Statamic\Fields\Field as StatamicField;

class FieldSynth extends Synth
{
    public static $key = 'field';

    public static function match($target)
    {
        return $target instanceof Field;
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

        return $meta['class']::make(
            field: new StatamicField($value['handle'], $value['config']),
            id: $this->context->component->getId()
        )
            ->properties($value['properties'])
            ->value($value['value'])
            ->submittable($value['submittable']);
    }

    public function get(&$target, $key)
    {
        return $target->get($key)->value();
    }

    public function set(&$target, $key, $value)
    {
        $target->get($key)->value($value);
    }
}
