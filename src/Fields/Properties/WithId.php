<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithId
{
    protected function idProperty(): string
    {
        return "{$this->component->getId()}-field-{$this->handle}";
    }
}
