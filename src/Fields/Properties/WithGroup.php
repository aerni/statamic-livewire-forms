<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithGroup
{
    protected function groupProperty(): string
    {
        return $this->field->get('group', 'undefined');
    }
}
