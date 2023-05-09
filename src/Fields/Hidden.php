<?php

namespace Aerni\LivewireForms\Fields;

class Hidden extends Field
{
    protected static string $view = 'input';

    protected function alwaysSaveProperty(): bool
    {
        return true;
    }

    protected function showProperty(): bool
    {
        return false;
    }
}
