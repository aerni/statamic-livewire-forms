<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\Component;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;

class Captcha extends Field
{
    use WithInstructions,
        WithLabel,
        WithShowLabel;

    public function viewProperty(): string
    {
        return Component::getView('fields.captcha');
    }
}
