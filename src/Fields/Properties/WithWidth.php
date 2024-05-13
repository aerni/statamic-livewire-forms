<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithWidth
{
    protected function widthProperty(?int $width = null): int
    {
        return $width ?? $this->field->get('width', 100);
    }
}
