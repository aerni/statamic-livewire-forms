<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithAutocomplete
{
    public function autocompleteProperty(): string
    {
        return $this->field->get('autocomplete') ?? 'off';
    }
}
