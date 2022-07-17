<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithKey
{
    protected function keyProperty(): string
    {
        return "data.{$this->handle()}";
    }
}
