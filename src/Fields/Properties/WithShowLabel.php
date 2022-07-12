<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Statamic\Support\Str;

trait WithShowLabel
{
    protected function showLabelProperty(): bool
    {
        return Str::toBool($this->field->get('show_label', true));
    }
}
