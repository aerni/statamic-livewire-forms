<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithWireModelModifier
{
    protected function wireModelModifierProperty(): string
    {
        return $this->field->get('wireModelModifier', 'lazy');
    }
}
