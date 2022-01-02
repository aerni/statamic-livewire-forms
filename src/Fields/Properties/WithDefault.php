<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithDefault
{
    public function defaultProperty(): mixed
    {
        return $this->field->defaultValue();
    }
}
