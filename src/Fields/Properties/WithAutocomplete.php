<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithAutocomplete
{
    protected function autocompleteProperty(?string $autocomplete = null): ?string
    {
        $autocomplete ??= $this->field->get('autocomplete');

        if (filled($autocomplete)) {
            return $autocomplete;
        }

        return match ($this->input_type) {
            'email' => 'email',
            'tel' => 'tel',
            'url' => 'url',
            default => null,
        };
    }
}
