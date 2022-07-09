<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Statamic\Support\Str;

trait WithShow
{
    public function showProperty(): bool
    {
        return Str::toBool($this->field->get('show', true));
    }
}
