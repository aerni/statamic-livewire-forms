<?php

namespace Aerni\LivewireForms\Fields;

class Captcha extends Field
{
    protected static string $view = 'captcha';

    public function process(mixed $value): mixed
    {
        return null;
    }
}
