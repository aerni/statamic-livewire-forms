<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithMultiple;

class Assets extends Field
{
    use WithMultiple;

    public function viewProperty(): string
    {
        return Component::getView('fields.file');
    }

    public function multipleProperty(): bool
    {
        return $this->field->get('max_files') !== 1;
    }

    public function defaultProperty(): array
    {
        // The Assets fieldtype expects an array. Validation fails if it's something else.
        return [];
    }
}
