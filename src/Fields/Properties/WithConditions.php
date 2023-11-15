<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithConditions
{
    protected function conditionsProperty(): string
    {
        return json_encode($this->field->conditions());
    }
}
