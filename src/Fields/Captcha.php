<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Facades\View;
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
        return View::get('fields.captcha');
    }
}
