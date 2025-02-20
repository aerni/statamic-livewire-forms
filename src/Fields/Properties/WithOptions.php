<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Illuminate\Support\Arr;

trait WithOptions
{
    protected function optionsProperty(?array $options = null): array
    {
        $options = $options ?? $this->field->get('options', []);

        if (Arr::isAssoc($options)) {
            return collect($options)
                ->map(fn ($value, $key) => __($value) ?? __($key))
                ->toArray();
        }

        return collect($options)
            ->mapWithKeys(fn ($value) => [$value['key'] => __($value['value']) ?? __($value['key'])])
            ->toArray();
    }
}
