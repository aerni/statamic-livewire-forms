<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithLabel
{
    protected function labelProperty(): ?string
    {
        return __($this->field->get('display'));
    }
}
