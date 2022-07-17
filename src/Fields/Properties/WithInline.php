<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInline
{
    protected function inlineProperty(): bool
    {
        return (bool) $this->field->get('inline');
    }
}
