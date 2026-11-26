<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Number extends Field
{
    use WithAutocomplete;
    use WithPlaceholder;

    protected string $view = 'number';

    protected function inputTypeProperty(): string
    {
        return 'number';
    }
}
