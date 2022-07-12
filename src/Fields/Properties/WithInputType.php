<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInputType
{
    protected function inputTypeProperty(): string
    {
        return $this->field->get('input_type', 'text');
    }
}
