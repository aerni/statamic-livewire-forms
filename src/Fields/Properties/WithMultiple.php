<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithMultiple
{
    public function multipleProperty(): bool
    {
        return (bool) $this->field->get('multiple');
    }
}
