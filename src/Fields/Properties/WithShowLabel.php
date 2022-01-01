<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Statamic\Support\Str;

trait WithShowLabel
{
    public function showLabelProperty(): bool
    {
        $showLabel = $this->field->get('show_label') ?? true;

        return Str::toBool($showLabel);
    }
}