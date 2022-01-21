<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;

class Captcha extends Field
{
    public function viewProperty(): string
    {
        return Component::getView('fields.captcha');
    }
}
