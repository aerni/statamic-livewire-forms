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

        return $default ?? array_key_first($options);
    }
}
