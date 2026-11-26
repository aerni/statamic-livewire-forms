<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Website extends Field
{
    use WithAutocomplete;
    use WithInputType;
    use WithPlaceholder;

    protected string $view = 'website';

    protected function hiddenProperty(?bool $hidden = null): bool
    {
        return $hidden ?? $this->input_type === 'hidden'
            ? true : parent::hiddenProperty();
    }
}
