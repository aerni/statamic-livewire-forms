<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Integer extends Field
{
    use WithAutocomplete;
    use WithInputType;
    use WithPlaceholder;
    use WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.input');
    }

    public function inputTypeProperty(): string
    {
        return 'number';
    }
}
