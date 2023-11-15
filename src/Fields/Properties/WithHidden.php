<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithHidden
{
    protected function hiddenProperty(): bool
    {
        return $this->field->get('hidden', false);
    }
}
