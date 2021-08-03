<?php

namespace Aerni\LivewireForms\Traits;

use Statamic\Fields\Field;
use Illuminate\Support\Arr;

trait HydratesData
{
    protected function hydrateData(): array
    {
        return $this->form->fields()->mapWithKeys(function ($field) {
            /**
             * Don't reset the captcha after the form has been submitted.
             * The captcha will expire and reset itself after a while.
             */
            if ($field->type() === 'captcha') {
                return [$field->handle() => Arr::get($this->data, $field->handle())];
            }

            // Set the default field values according to the form blueprint.
            return [$field->handle() => $this->assignDefaultFieldValue($field)];
        })->put($this->form->honeypot(), null)->toArray();
    }

    protected function assignDefaultFieldValue(Field $field)
    {
        if ($field->type() === 'checkboxes') {
            return $this->getDefaultCheckboxValue($field);
        }

        if ($field->type() === 'select') {
            return $this->getDefaultSelectValue($field);
        }

        /**
         * Make sure to always return the first array value if someone set the default value
         * to an array instead of a string or integer.
        */
        return array_first((array) $field->defaultValue());
    }

    protected function getDefaultCheckboxValue(Field $field)
    {
        $default = $field->defaultValue();
        $options = $field->get('options');

        return (count($options) > 1)
            ? (array) $default
            : array_first((array) $default);
    }

    protected function getDefaultSelectValue(Field $field): string
    {
        $default = $field->defaultValue();
        $options = $field->get('options');

        return $default ?? array_key_first($options);
    }
}
