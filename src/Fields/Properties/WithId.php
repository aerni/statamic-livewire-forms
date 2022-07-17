<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithId
{
    protected function idProperty(): string
    {
        return "{$this->id}_{$this->handle()}";
    }
}
