<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;

class Radio extends Field
{
    use WithCastBooleans;
    use WithInline;
    use WithOptions;

    protected string $view = 'radio';

    protected function defaultProperty(): ?string
    {
        $default = $this->field->defaultValue();
        $options = $this->options;

        // A default is only valid if it exists in the options.
        return collect($options)->only($default ?? [])->keys()->first();
    }
}
