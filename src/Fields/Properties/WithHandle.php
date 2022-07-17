<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithHandle
{
    protected function handleProperty(): string
    {
        return $this->field->handle();
    }
}
