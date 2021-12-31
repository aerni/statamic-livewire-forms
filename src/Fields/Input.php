<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Input extends Field
{
    use WithAutocomplete,
        WithInputType,
        WithPlaceholder,
        WithShowLabel;
}
