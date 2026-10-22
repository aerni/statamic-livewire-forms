<?php

namespace Aerni\LivewireForms\Fields;

use Statamic\Facades\Icon;

class Info extends Field
{
    protected string $view = 'banner';

    protected function iconProperty(?string $icon = null): ?string
    {
        $icon ??= $this->field->get('icon');

        return $icon ? Icon::default()->get($icon) : null;
    }
}
