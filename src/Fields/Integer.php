<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Integer extends Field
{
    use WithAutocomplete;
    use WithInputType;
    use WithPlaceholder;

    protected string $view = 'default';

    protected function inputTypeProperty(): string
    {
        return 'number';
    }

    public function process(mixed $value): mixed
    {
        return is_null($value) ? null : (int) $value;
    }
}
