<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Select extends Field
{
    use WithCastBooleans,
        WithInstructions,
        WithOptions,
        WithShowLabel;

    public function viewProperty(): string
    {
        return 'fields.select';
    }

    public function defaultProperty(): string
    {
        $default = $this->field->defaultValue();
        $options = $this->field->get('options');

        return $default ?? array_key_first($options);
    }
}
