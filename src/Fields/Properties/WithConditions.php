<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithConditions
{
    protected function conditionsProperty(?array $conditions = null): array
    {
        return $conditions ?? $this->field->conditions();
    }
}
