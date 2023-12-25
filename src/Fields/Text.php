<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithInputType;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Text extends Field
{
    use WithAutocomplete;
    use WithInputType;
    use WithPlaceholder;

    protected string $view = 'default';

    protected function hiddenProperty(): bool
    {
        return $this->input_type === 'hidden'
            ? true
            : parent::hiddenProperty();
    }

    public function process(): mixed
    {
        if ($this->input_type === 'number') {
            return is_null($this->value) ? null : (int) $this->value;
        }

        return $this->value;
    }
}
