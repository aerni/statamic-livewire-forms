<?php

namespace Aerni\LivewireForms\Fields;

class Hidden extends Field
{
    protected static string $view = 'input';

    protected function showProperty(): bool
    {
        return false;
    }
}
