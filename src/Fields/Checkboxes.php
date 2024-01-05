<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;

class Checkboxes extends Field
{
    use WithInline;
    use WithOptions;

    protected string $view = 'checkboxes';

    protected function defaultProperty(string|array|null $default = null): array
    {
        return collect($this->options)
            ->only($default ?? $this->field->defaultValue() ?? [])
            ->keys()
            ->toArray();
    }
}
