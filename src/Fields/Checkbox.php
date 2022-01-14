<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Checkbox extends Field
{
    use WithInline;
    use WithInstructions;
    use WithLabel;
    use WithOptions;
    use WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.checkbox');
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
