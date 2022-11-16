<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Text extends Field
{
    use WithAutocomplete;
    use WithInputType;
    use WithPlaceholder;

    protected static string $view = 'input';

    protected function showProperty(): bool
    {
        if ($this->inputTypeProperty() === 'hidden') {
            return false;
        }

        return parent::showProperty();
    }
}
