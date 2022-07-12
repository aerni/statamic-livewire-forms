<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithRules
{
    protected function rulesProperty(): array
    {
        return array_flatten($this->field->rules());
    }
}
