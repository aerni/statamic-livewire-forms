<?php

namespace Aerni\LivewireForms\Fields;

class Date extends Field
{
    protected string $view = 'default';

    protected function inputTypeProperty(): string
    {
        return $this->field->get('time_enabled') ? 'datetime-local' : 'date';
    }
}
