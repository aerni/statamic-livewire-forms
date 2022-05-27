<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithInlineLabel;

class Toggle extends Field
{
    use WithInlineLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.toggle');
    }

    public function rulesProperty(): array
    {
        $rules = collect(parent::rulesProperty());

        if ($rules->contains('required')) {
            $rules->push('accepted');
        }

        return $rules->all();
    }
}
