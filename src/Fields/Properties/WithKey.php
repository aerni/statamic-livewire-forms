<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithKey
{
    public function keyProperty(): string
    {
        return "data.{$this->field->handle()}";
    }
}
