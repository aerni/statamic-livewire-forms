<?php

namespace Aerni\LivewireForms\Fields;

class Captcha extends Field
{
    protected string $view = 'captcha';

    public function process(mixed $value): mixed
    {
        return null;
    }
}
