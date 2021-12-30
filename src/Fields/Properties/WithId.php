<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithId
{
    public function id(): string
    {
        return uniqid() . '_' . $this->field->handle();
    }
}
