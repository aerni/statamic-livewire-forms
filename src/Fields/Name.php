<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Name extends Field
{
    use WithAutocomplete;
    use WithInputType;
    use WithPlaceholder;

    protected string $view = 'name';

    protected function hiddenProperty(?bool $hidden = null): bool
    {
        return $hidden ?? $this->input_type === 'hidden'
            ? true : parent::hiddenProperty();
    }
}
