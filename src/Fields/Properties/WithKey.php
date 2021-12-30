<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithKey
{
    public function key(): string
    {
        return "data.{$this->field->handle()}";
    }
}
