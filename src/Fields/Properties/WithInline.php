<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInline
{
    protected function inlineProperty(?bool $inline = null): bool
    {
        return $inline ?? (bool) $this->field->get('inline');
    }
}
