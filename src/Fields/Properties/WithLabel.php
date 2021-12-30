<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithLabel
{
    public function label(): ?string
    {
        return __($this->field->get('display'));
    }
}
