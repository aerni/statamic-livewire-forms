<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class ShortAnswer extends Field
{
    use WithAutocomplete;
    use WithInputType;
    use WithPlaceholder;

    protected string $view = 'short_answer';

    protected function hiddenProperty(?bool $hidden = null): bool
    {
        return $hidden ?? $this->input_type === 'hidden'
            ? true : parent::hiddenProperty();
    }
}
