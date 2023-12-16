<?php

namespace Aerni\LivewireForms\Fields;

class Hidden extends Field
{
    protected string $view = 'default';

    protected function hiddenProperty(): bool
    {
        return true;
    }
}
