<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInstructions
{
    protected function instructionsProperty(): ?string
    {
        return __($this->field->get('instructions'));
    }
}
