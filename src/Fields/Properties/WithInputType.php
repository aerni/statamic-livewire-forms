<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInputType
{
    protected function inputTypeProperty(?string $inputType = null): string
    {
        return $inputType ?? $this->field->get('input_type', 'text');
    }
}
