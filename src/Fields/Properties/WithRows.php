<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithRows
{
    protected function rowsProperty(): ?int
    {
        return $this->field->get('rows');
    }
}
