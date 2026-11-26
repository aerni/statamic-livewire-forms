<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;

class MultiChoice extends Field
{
    use WithInline;
    use WithOptions;

    protected string $view = 'multi_choice';

    protected function defaultProperty(mixed $default = null): ?string
    {
        return collect($this->options)
            ->only($default ?? $this->field->defaultValue() ?? [])
            ->keys()
            ->first();
    }
}
