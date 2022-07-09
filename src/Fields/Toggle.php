<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInlineLabel;

class Toggle extends Field
{
    use WithInlineLabel;

    protected string $view = 'toggle';

    public function rulesProperty(): array
    {
        $rules = collect(parent::rulesProperty());

        if ($rules->contains('required')) {
            $rules->push('accepted');
        }

        return $rules->all();
    }
}
