<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Statamic\Support\Str;

trait WithCastBooleans
{
    protected function castBooleansProperty(): bool
    {
        return Str::toBool($this->field->get('cast_booleans'));
    }
}
