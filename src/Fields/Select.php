<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithCastBooleans;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Select extends Field
{
    use WithCastBooleans,
        WithInstructions,
        WithOptions,
        WithShowLabel;
}
