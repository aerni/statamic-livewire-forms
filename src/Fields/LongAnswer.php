<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;
use Aerni\LivewireForms\Fields\Properties\WithRows;

class LongAnswer extends Field
{
    use WithAutocomplete;
    use WithPlaceholder;
    use WithRows;

    protected string $view = 'long_answer';
}
