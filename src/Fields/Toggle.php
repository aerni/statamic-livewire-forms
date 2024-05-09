<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInlineLabel;
use Illuminate\Support\Arr;

class Toggle extends Field
{
    use WithInlineLabel;

    protected string $view = 'toggle';

    protected function rulesProperty(string|array|null $rules = null): array
    {
        $rules = Arr::first(parent::rulesProperty($rules));

        if (in_array('required', $rules)) {
            $rules[] = 'accepted';
        }

        return [$this->key => $rules];
    }
}
