<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithRows;

class Textarea extends Field
{
    use WithAutocomplete;
    use WithPlaceholder;
    use WithRows;

    protected string $view = 'textarea';
}
