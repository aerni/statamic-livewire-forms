<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithKey
{
    protected function keyProperty(): string
    {
        return "fields.{$this->handle()}.value";
    }
}
