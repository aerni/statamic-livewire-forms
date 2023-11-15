<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithAlwaysSave
{
    protected function alwaysSaveProperty(): bool
    {
        return $this->field->get('always_save', false);
    }
}
