<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithInputType
{
    public function inputType(): string
    {
        return match ($this->field->type()) {
            'assets' => 'file',
            'integer' => 'number',
            'text' => $this->field->get('input_type') ?? 'text',
            default => 'text',
        };
    }
}
