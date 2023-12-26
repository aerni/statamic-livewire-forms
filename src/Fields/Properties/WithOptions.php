<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Illuminate\Support\Arr;

trait WithOptions
{
    protected function optionsProperty(): array
    {
        $options = $this->field->get('options');

        if (Arr::isAssoc($options)) {
            return collect($options)
                ->map(fn ($value, $key) => __($value) ?? __($key))
                ->toArray();
        }

        return collect($options)
            ->mapWithKeys(fn ($value) => [$value => __($value)])
            ->toArray();
    }
}
