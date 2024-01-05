<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInlineLabel
{
    protected function inlineLabelProperty(?string $inlineLabel = null): ?string
    {
        return __($inlineLabel ?? $this->field->get('inline_label'));
    }
}
