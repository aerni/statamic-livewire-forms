<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithLabel
{
    protected function labelProperty(?string $label = null): ?string
    {
        return __($label ?? $this->field->get('display'));
    }
}
