<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithMultiple
{
    protected function multipleProperty(?bool $multiple = null): bool
    {
        return $multiple ?? (bool) $this->field->get('multiple');
    }
}
