<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithDefault
{
    public function default(): array|string|null
    {
        return match ($this->field->type()) {
            'checkboxes' => $this->getDefaultCheckboxValue(),
            'select' => $this->getDefaultSelectValue(),

            /**
             * Make sure to always return the first array value if someone set the default
             * to an array instead of a string or integer.
            */
            default => array_first((array) $this->field->defaultValue()),
        };
    }

    protected function defaultCheckboxValue(): array|string
    {
        $default = $this->field->defaultValue();
        $options = $this->field->get('options');

        return (count($options) > 1)
            ? (array) $default
            : array_first((array) $default);
    }

    protected function defaultSelectValue(): string
    {
        $default = $this->field->defaultValue();
        $options = $this->field->get('options');

        return $default ?? array_key_first($options);
    }
}
