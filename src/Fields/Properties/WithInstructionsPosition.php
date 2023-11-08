<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInstructionsPosition
{
    protected function instructionsPositionProperty(): string
    {
        return $this->field->get('instructions_position', 'above');
    }
}
