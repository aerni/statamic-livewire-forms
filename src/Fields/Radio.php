<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;

class Radio extends Field
{
    use WithInline;
    use WithOptions;

    protected string $view = 'radio';

    protected function defaultProperty(): ?string
    {
        return collect($this->options)
            ->only($this->field->defaultValue() ?? [])
            ->keys()
            ->first();
    }
}
