<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithHidden
{
    protected function hiddenProperty(?bool $hidden = null): bool
    {
        return $hidden ?? $this->field->get('hidden', false);
    }
}
