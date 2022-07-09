<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInlineLabel
{
    public function inlineLabelProperty(): ?string
    {
        return __($this->field->get('inline_label'));
    }
}
