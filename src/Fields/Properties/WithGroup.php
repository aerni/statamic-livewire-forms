<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithGroup
{
    public function group(): string
    {
        return $this->field->get('group') ?? 'undefined';
    }
}
