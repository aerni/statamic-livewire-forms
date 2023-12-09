<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithMultiple;

class Assets extends Field
{
    use WithMultiple;

    protected string $view = 'assets';

    protected function multipleProperty(): bool
    {
        return $this->field->get('max_files') !== 1;
    }

    protected function defaultProperty(): ?array
    {
        return $this->multipleProperty() ? [] : null;
    }

    protected function rulesProperty(): array
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
