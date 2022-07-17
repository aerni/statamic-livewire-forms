<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithMultiple
{
    protected function multipleProperty(): bool
    {
        return (bool) $this->field->get('multiple');
    }
}
