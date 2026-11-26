<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;

class YesNo extends Field
{
    use WithInline;
    use WithOptions;

    protected string $view = 'yes_no';

    protected function defaultProperty(mixed $default = null): ?string
    {
        return collect($this->options)
            ->only($default ?? $this->field->defaultValue() ?? [])
            ->keys()
            ->first();
    }
}
