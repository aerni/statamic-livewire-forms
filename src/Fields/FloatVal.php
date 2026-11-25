<?php

namespace Aerni\LivewireForms\Fields;

class FloatVal extends Integer
{
    protected function stepProperty(): mixed
    {
        return $this->field->get('step', 'any');
    }
}
