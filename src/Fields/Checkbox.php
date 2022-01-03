<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\View;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;

class Checkbox extends Field
{
    use WithInline,
        WithInstructions,
        WithLabel,
        WithOptions,
        WithShowLabel;

    public function viewProperty(): string
    {
        return View::get('fields.checkbox');
    }

    public function defaultProperty(): array|string
    {
        $default = $this->field->defaultValue();
        $options = $this->optionsProperty();

        return (count($options) > 1)
            ? (array) $default
            : array_first((array) $default);
    }
}
