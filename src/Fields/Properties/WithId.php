<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithId
{
    public function idProperty(): string
    {
        return "{$this->id}_{$this->field->handle()}";
    }
}
