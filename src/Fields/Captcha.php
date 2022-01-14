<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;

class Captcha extends Field
{
    use WithInstructions;
    use WithLabel;
    use WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.captcha');
    }
}
