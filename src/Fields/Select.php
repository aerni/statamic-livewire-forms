<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithMultiple;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Select extends Field
{
    use WithAutocomplete;
    use WithCastBooleans;
    use WithMultiple;
    use WithOptions;
    use WithPlaceholder;

    protected string $view = 'select';

    public function defaultProperty(): string|array|null
    {
        $default = $this->field->defaultValue();
        $options = $this->optionsProperty();

        // A default is only valid if it exists in the options.
        $default = collect($options)->only($default ?? [])->keys();

        // Return all defaults if the Select field has multiple enabled.
        if ($this->multipleProperty()) {
            return $default->toArray();
        }

        // If there are any defaults, return the first.
        if ($default->isNotEmpty()) {
            return $default->first();
        }

        // If there is a placeholder we don't want to return a default.
        if ($this->placeholderProperty()) {
            return null;
        }

        // Fall back to simply return the first option.
        return array_key_first($options);
    }
}
