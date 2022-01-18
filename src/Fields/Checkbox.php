<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;

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

    public function defaultProperty(): array
    {
        $default = $this->field->defaultValue();
        $options = $this->optionsProperty();

        // A default is only valid if it exists in the options.
        return collect($options)->only($default ?? [])->keys()->toArray();
    }
}
