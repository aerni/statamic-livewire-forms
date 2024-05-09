<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithDisplay
{
    protected function displayProperty(?string $display = null): ?string
    {
        return __($display ?? $this->field->get('display'));
    }
}
