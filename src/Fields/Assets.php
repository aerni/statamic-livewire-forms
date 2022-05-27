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

    public function defaultProperty(): ?array
    {
        return $this->multipleProperty() ? [] : null;
    }

    public function rulesProperty(): array
    {
        $rules = parent::rulesProperty();

        if ($this->multipleProperty()) {
            return $rules;
        }

        return collect($rules)
            ->filter(fn ($rule) => ! in_array($rule, ['array', 'max:1']))
            ->all();
    }
}
