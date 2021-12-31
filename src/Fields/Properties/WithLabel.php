<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithLabel
{
    public function labelProperty(): ?string
    {
        return __($this->field->get('display'));
    }
}
