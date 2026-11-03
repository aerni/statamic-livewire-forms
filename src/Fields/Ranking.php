<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithOptions;

class Ranking extends Field
{
    use WithOptions;

    protected string $view = 'ranking';

    protected function defaultProperty(mixed $default = null): array
    {
        /* A ranking always has an order, so every option is part of the value from the start. */
        return $default ?? array_keys($this->options);
    }
}
