<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithAlwaysSave
{
    protected function alwaysSaveProperty(): bool
    {
        return $this->field->alwaysSave();
    }
}
