<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Select;
use Statamic\Dictionaries\Dictionary as DictionaryInstance;
use Statamic\Exceptions\DictionaryNotFoundException;
use Statamic\Exceptions\UndefinedDictionaryException;
use Statamic\Facades\Dictionary as Dictionaries;
use Statamic\Support\Arr;

class Dictionary extends Select
{
    protected string $view = 'dictionary';

    protected function multipleProperty(?bool $multiple = null): bool
    {
        return $multiple ?? is_null($this->max_items) || $this->max_items > 1;
    }

    public function dictionary(): ?DictionaryInstance
    {
        $config = is_array($config = $this->field->get('dictionary')) ? $config : ['type' => $config];

        return Dictionaries::find($config['type'], $config);
    }

    public function optionsProperty(?array $options = null): array
    {
        return $this->dictionary()?->options() ?? [];
    }
}
