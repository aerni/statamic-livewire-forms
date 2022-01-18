<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithMultiple;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Illuminate\Support\Arr;

class Select extends Field
{
    use WithAutocomplete;
    use WithCastBooleans;
    use WithInstructions;
    use WithMultiple;
    use WithOptions;
    use WithPlaceholder;
    use WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.select');
    }

    public function defaultProperty(): string
    {
        $default = $this->field->defaultValue();
        $options = $this->optionsProperty();

        // A default is only valid if it exists in the options.
        $default = collect($options)->only($default ?? [])->keys()->first();

        // If there is no default, we want to fall back to the placeholder or first option.
        return $default ?? $this->placeholderProperty() ?? array_key_first($options);
    }
}
