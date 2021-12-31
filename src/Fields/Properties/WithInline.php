<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInline
{
    public function inlineProperty(): bool
    {
        return (bool) $this->field->get('inline');
    }
}
