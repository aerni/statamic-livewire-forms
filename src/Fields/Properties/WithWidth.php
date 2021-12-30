<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithWidth
{
    public function width(): int
    {
        return $this->field->get('width') ?? 100;
    }
}
