<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithPlaceholder
{
    public function placeholder(): ?string
    {
        return __($this->field->get('placeholder'));
    }
}
