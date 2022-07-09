<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithWireModelModifier
{
    public function wireModelModifierProperty(): string
    {
        return $this->field->get('wireModelModifier', 'lazy');
    }
}
