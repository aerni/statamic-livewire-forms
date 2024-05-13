<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithAutocomplete
{
    protected function autocompleteProperty(?string $autocomplete = null): ?string
    {
        return $autocomplete ?? $this->field->get('autocomplete');
    }
}
