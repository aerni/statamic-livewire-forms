<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithDefault
{
    protected function defaultProperty(mixed $default = null): mixed
    {
        return $default ?? $this->field->defaultValue();
    }
}
