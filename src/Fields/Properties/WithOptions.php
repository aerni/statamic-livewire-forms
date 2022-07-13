<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithOptions
{
    protected function optionsProperty(): array
    {
        return collect($this->field->get('options'))
            ->mapWithKeys(fn ($value, $key) => is_numeric($key) ? [$value => __($value)] : [$key => __($value)])
            ->toArray();
    }
}
