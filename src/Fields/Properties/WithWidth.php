<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithWidth
{
    public function widthProperty(): int
    {
        return $this->field->get('width', 100);
    }
}
