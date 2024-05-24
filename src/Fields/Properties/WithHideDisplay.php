<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithHideDisplay
{
    protected function hideDisplayProperty(?bool $hideDisplay = null): bool
    {
        return $hideDisplay ?? (bool) $this->field->get('hide_display');
    }
}
