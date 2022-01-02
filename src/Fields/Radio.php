<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Radio extends Field
{
    use WithCastBooleans,
        WithInline,
        WithInstructions,
        WithOptions,
        WithShowLabel;

    public function view(): string
    {
        return 'fields.radios';
    }
}
