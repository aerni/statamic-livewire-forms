<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\View;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;

class Textarea extends Field
{
    use WithAutocomplete,
        WithInstructions,
        WithPlaceholder,
        WithShowLabel;

    public function viewProperty(): string
    {
        return View::get('fields.textarea');
    }
}
