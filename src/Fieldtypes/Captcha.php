<?php

namespace Aerni\LivewireForms\Fieldtypes;

use Statamic\Fields\Fieldtype;

class Captcha extends Fieldtype
{
    protected $localizable = false;
    protected $defaultable = false;
    protected $selectable = false;
    protected $selectableInForms = true;
    protected $rules = ['required', 'captcha'];
    protected $icon = 'lock';
}
