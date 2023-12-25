<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Integer extends Field
{
    use WithAutocomplete;
    use WithPlaceholder;

    protected string $view = 'default';

    protected function inputTypeProperty(): string
    {
        return 'number';
    }

    public function process(): mixed
    {
        return is_null($this->value) ? null : (int) $this->value;
    }
}
