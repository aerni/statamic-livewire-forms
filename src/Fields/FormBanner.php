<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Support\Arr;

class FormBanner extends Field
{
    protected string $view = 'banner';

    protected function iconProperty(?string $icon = null): ?string
    {
        return $icon ?? Arr::get($this->field->fieldtype()->extraRenderableFieldData(), 'icon');
    }
}
