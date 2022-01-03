<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Textarea extends Field
{
    use WithAutocomplete,
        WithInstructions,
        WithPlaceholder,
        WithShowLabel;

    public function viewProperty(): string
    {
        return 'fields.textarea';
    }
}
