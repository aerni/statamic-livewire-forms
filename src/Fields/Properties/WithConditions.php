<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithConditions
{
    protected function conditionsProperty(): array
    {
        return $this->field->conditions();
    }
}
