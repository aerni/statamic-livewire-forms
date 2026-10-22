<?php

namespace Aerni\LivewireForms\Fields;

use Statamic\Rules\TimeFieldtype;

class Time extends Field
{
    protected string $view = 'default';

    protected function inputTypeProperty(): string
    {
        return 'time';
    }

    /**
     * Statamic's TimeFieldtype rule object can't be serialized by Livewire,
     * which is why we swap it out for the equivalent string-based rule.
     */
    protected function rulesProperty(string|array|null $rules = null): array
    {
        $format = $this->field->get('seconds_enabled') ? 'H:i:s' : 'H:i';

        return collect(parent::rulesProperty($rules))
            ->map(fn ($rules) => collect($rules)
                ->map(fn ($rule) => $rule instanceof TimeFieldtype ? "date_format:{$format}" : $rule)
                ->all())
            ->all();
    }
}
