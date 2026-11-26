<?php

namespace Aerni\LivewireForms\Fieldtypes;

use Statamic\Fields\Fieldtype;

class Captcha extends Fieldtype
{
    protected $selectable = false;

    protected $rules = ['required', 'captcha'];

    public function process($data)
    {
        return null;
    }
}
