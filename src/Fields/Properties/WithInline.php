<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInline
{
    public function inline(): bool
    {
        return (bool) $this->field->get('inline');
    }
}
