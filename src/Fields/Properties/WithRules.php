<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithRules
{
    public function rulesProperty(): array
    {
        return array_flatten($this->field->rules());
    }
}
