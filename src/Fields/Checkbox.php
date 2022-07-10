<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithInline;
use Aerni\LivewireForms\Fields\Properties\WithOptions;

class Checkbox extends Field
{
    use WithInline;
    use WithOptions;

    public const VIEW = 'checkbox';

    public function defaultProperty(): string|array|null
    {
        $default = $this->field->defaultValue();
        $options = $this->optionsProperty();

        // A default is only valid if it exists in the options.
        $default = collect($options)->only($default ?? [])->keys();

        /**
         * We want to save the submission data as an array if there is more than one option.
         * To do this, we need to initialize the default data to an array.
         * The array may contain valid defaults or be empty.
         */
        if (count($options) > 1) {
            return $default->toArray();
        }

        /**
         * If this is a single checkbox, we want to save the submission data as a string.
         * We also want to initialize the data to the first default if there is one.
         */
        return $default->first();
    }
}
