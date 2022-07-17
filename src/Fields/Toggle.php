<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInlineLabel;

class Toggle extends Field
{
    use WithInlineLabel;

    protected static string $view = 'toggle';

    protected function rulesProperty(): array
    {
        $rules = collect(parent::rulesProperty());

        if ($rules->contains('required')) {
            $rules->push('accepted');
        }

        return $rules->all();
    }
}
