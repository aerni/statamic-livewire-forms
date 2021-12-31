<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithPlaceholder
{
    public function placeholderProperty(): ?string
    {
        return __($this->field->get('placeholder'));
    }
}
