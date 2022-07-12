<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInlineLabel
{
    protected function inlineLabelProperty(): ?string
    {
        return __($this->field->get('inline_label'));
    }
}
