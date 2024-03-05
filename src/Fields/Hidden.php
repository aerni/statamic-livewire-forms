<?php

namespace Aerni\LivewireForms\Fields;

class Hidden extends Field
{
    protected string $view = 'default';

    protected function hiddenProperty(?bool $hidden = null): bool
    {
        return true;
    }
}
