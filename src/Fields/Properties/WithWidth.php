<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithWidth
{
    protected function widthProperty(): int
    {
        return $this->field->get('width', 100);
    }
}
