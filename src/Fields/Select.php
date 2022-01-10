<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;

class Select extends Field
{
    use WithAutocomplete,
        WithCastBooleans,
        WithInstructions,
        WithOptions,
        WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.select');
    }

    public function defaultProperty(): string
    {
        $default = $this->field->defaultValue();
        $options = $this->optionsProperty();

        return $default ?? array_key_first($options);
    }
}
