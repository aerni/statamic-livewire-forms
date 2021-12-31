<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithConditions
{
    public function conditionsProperty(): array
    {
        return array_filter([
            'if' => $this->field->get('if'),
            'if_any' => $this->field->get('if_any'),
            'unless' => $this->field->get('unless'),
            'unless_any' => $this->field->get('unless_any'),
        ]);
    }
}
