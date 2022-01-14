<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Textarea extends Field
{
    use WithAutocomplete;
    use WithInstructions;
    use WithPlaceholder;
    use WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.textarea');
    }
}
