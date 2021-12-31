<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithHandle
{
    public function handleProperty(): string
    {
        return $this->field->handle();
    }
}
