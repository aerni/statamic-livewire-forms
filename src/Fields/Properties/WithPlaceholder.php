<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithPlaceholder
{
    protected function placeholderProperty(?string $placeholder = null): ?string
    {
        return __($placeholder ?? $this->field->get('placeholder'));
    }
}
