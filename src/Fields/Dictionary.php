<?php

namespace Aerni\LivewireForms\Fields;

use Statamic\Facades\Dictionary as Dictionaries;

class Dictionary extends Select
{
    protected string $view = 'dictionary';

    protected function multipleProperty(?bool $multiple = null): bool
    {
        return $multiple ?? is_null($this->max_items) || $this->max_items > 1;
    }

    protected function optionsProperty(?array $options = null): array
    {
        $config = is_array($config = $this->field->get('dictionary')) ? $config : ['type' => $config];

        $dictionary = Dictionaries::find($config['type'], $config);

        return $dictionary?->options() ?? [];
    }
}
