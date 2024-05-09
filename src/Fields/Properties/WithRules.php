<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Illuminate\Support\Arr;
use Statamic\Fields\Validator;

trait WithRules
{
    protected function rulesProperty(string|array|null $rules = null): array
    {
        $rules = is_null($rules)
            ? Arr::flatten($this->field->rules())
            : Validator::explodeRules($rules);

        return [$this->key => $rules];
    }
}
