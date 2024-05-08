<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithAutocomplete;
use Aerni\LivewireForms\Fields\Properties\WithMultiple;
use Aerni\LivewireForms\Fields\Properties\WithOptions;
use Aerni\LivewireForms\Fields\Properties\WithPlaceholder;

class Select extends Field
{
    use WithAutocomplete;
    use WithMultiple;
    use WithOptions;
    use WithPlaceholder;

    protected string $view = 'select';

    protected function defaultProperty(mixed $default = null): string|array|null
    {
        $default = $default ?? $this->field->defaultValue();
        $options = $this->options;

        /* A default is only valid if it exists in the options. */
        $default = collect($options)->only($default ?? [])->keys();

        /* Return all defaults if the Select field has multiple enabled. */
        if ($this->multiple) {
            return $default->toArray();
        }

        /* If there are any defaults, return the first. */
        if ($default->isNotEmpty()) {
            return $default->first();
        }

        /* If there is a placeholder we don't want to return a default. */
        if ($this->placeholder) {
            return null;
        }

        /* Fall back to simply return the first option. */
        return array_key_first($options);
    }

    protected function rulesProperty(string|array|null $rules = null): array
    {
        $rules = array_first(parent::rulesProperty($rules));

        if ($this->multiple && $this->max_items) {
            $rules[] = "max:{$this->max_items}";
        }

        return [$this->key => $rules];
    }
}
