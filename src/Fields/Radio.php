<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Radio extends Field
{
    use WithCastBooleans;
    use WithInline;
    use WithInstructions;
    use WithOptions;
    use WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.radio');
    }

    public function defaultProperty(): ?string
    {
        $default = $this->field->defaultValue();
        $options = $this->optionsProperty();

        // Only get defaults values if they exist in the options.
        $validDefaults = collect($options)->only($default);

        // Get the first valid default and respect the order of the defaults.
        return collect($default)->first(function ($value) use ($validDefaults) {
            return $validDefaults->has($value);
        });
    }
}
