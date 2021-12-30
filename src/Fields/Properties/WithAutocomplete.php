<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithAutocomplete
{
    public function autocomplete(): string
    {
        return $this->field->get('autocomplete') ?? 'off';
    }
}
