<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Textarea extends Field
{
    use WithAutocomplete;
    use WithPlaceholder;

    const VIEW = 'textarea';
}
