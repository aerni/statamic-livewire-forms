<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInstructions
{
    protected function instructionsProperty(?string $instructions = null): ?string
    {
        return __($instructions ?? $this->field->get('instructions'));
    }
}
