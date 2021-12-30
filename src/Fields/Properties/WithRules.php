<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithRules
{
    public function rules(): array
    {
        return array_flatten($this->field->rules());
    }
}
