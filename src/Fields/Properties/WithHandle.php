<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithHandle
{
    public function handle(): string
    {
        return $this->field->handle();
    }
}
