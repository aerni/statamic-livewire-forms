<?php

namespace Aerni\LivewireForms\Fields;

class DatePicker extends Field
{
    protected string $view = 'date_picker';

    protected function inputTypeProperty(): string
    {
        return $this->field->get('time_enabled') ? 'datetime-local' : 'date';
    }
}
