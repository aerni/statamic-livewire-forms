<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithRows
{
    protected function rowsProperty(?int $rows = null): ?int
    {
        return $rows ?? $this->field->get('rows');
    }
}
