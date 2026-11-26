<?php

namespace Aerni\LivewireForms\Fieldtypes\Forms;

use Statamic\Forms\Fields\FormFieldtype;
use Statamic\Support\Arr;

class Captcha extends FormFieldtype
{
    protected static $fieldtype = 'captcha';

    protected $description = 'A captcha to protect your form from spam.';

    protected $icon = 'security-lock';

    public function toFieldArray(): array
    {
        return [
            'type' => 'captcha',
            ...Arr::except($this->config() ?? [], ['type']),
        ];
    }
}
