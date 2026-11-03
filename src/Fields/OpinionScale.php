<?php

namespace Aerni\LivewireForms\Fields;

class OpinionScale extends Field
{
    protected string $view = 'opinion_scale';

    protected function scaleValuesProperty(?array $scaleValues = null): array
    {
        return $scaleValues ?? range($this->field->get('min', 0), $this->field->get('max', 10));
    }
}
