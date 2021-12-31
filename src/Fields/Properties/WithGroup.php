<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithGroup
{
    public function groupProperty(): string
    {
        return $this->field->get('group') ?? 'undefined';
    }
}
