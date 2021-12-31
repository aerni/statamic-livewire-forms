<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithOptions
{
    public function optionsProperty(): array
    {
        return collect($this->field->get('options'))
            ->map(fn ($option) => __($option))
            ->toArray();
    }
}
