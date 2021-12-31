<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInputType
{
    public function inputTypeProperty(): string
    {
        return match ($this->field->type()) {
            'assets' => 'file',
            'integer' => 'number',
            'text' => $this->field->get('input_type') ?? 'text',
            default => 'text',
        };
    }
}
