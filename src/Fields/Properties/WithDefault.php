<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithDefault
{
    protected function defaultProperty(): mixed
    {
        return $this->field->defaultValue();
    }
}
