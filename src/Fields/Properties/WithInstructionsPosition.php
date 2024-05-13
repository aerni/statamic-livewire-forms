<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInstructionsPosition
{
    protected function instructionsPositionProperty(?string $instructionsPosition = null): string
    {
        return $instructionsPosition ?? $this->field->get('instructions_position', 'above');
    }
}
