<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithWireModel
{
    protected function wireModelProperty(?string $wireModel = null): ?string
    {
        $wireModel = $wireModel ?? $this->field->get('wire_model', 'change');

        // "Defer" is Livewire's default so we don't want to return it as modifier.
        return $wireModel !== 'defer' ? $wireModel : null;
    }
}
