<?php

namespace Aerni\LivewireForms\Facades;

use Illuminate\Support\Facades\Facade;

class Conditions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\LivewireForms\Form\Conditions::class;
    }
}
