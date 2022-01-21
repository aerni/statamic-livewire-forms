<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Textarea extends Field
{
    use WithAutocomplete;
    use WithInstructions;
    use WithPlaceholder;

    public function viewProperty(): string
    {
        return Component::getView('fields.textarea');
    }
}
