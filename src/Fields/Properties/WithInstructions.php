<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInstructions
{
    public function instructions(): ?string
    {
        return __($this->field->get('instructions'));
    }
}
