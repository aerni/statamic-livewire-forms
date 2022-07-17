<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithAutocomplete
{
    protected function autocompleteProperty(): string
    {
        return $this->field->get('autocomplete', 'on');
    }
}
