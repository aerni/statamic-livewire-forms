<?php

namespace Aerni\LivewireForms\Fieldtypes\Forms;

use Statamic\Forms\Fields\FormFieldtype;
use Statamic\Support\Arr;

use function Statamic\trans as __;

class Hidden extends FormFieldtype
{
    protected static $fieldtype = 'hidden';

    protected $description = 'Stores a value without showing it, such as the user\'s locale.';

    protected $icon = 'fieldtype-hidden';

    protected function configFieldItems(): array
    {
        return [
            'instructions' => ['type' => 'hidden'],
            'default' => [
                'display' => __('Default Value'),
                'instructions' => __('statamic::messages.fields_default_instructions'),
                'type' => 'text',
            ],
        ];
    }

    public function toFieldArray(): array
    {
        return [
            'type' => 'hidden',
            ...Arr::except($this->config() ?? [], ['type']),
        ];
    }
}
