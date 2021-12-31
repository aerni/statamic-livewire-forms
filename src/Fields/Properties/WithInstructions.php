<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInstructions
{
    public function instructionsProperty(): ?string
    {
        return __($this->field->get('instructions'));
    }
}
