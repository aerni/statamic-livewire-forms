<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;

class Captcha extends Field
{
    use WithInstructions;

    public function viewProperty(): string
    {
        return Component::getView('fields.captcha');
    }
}
