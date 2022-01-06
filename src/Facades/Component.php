<?php

namespace Aerni\LivewireForms\Facades;

use Illuminate\Support\Facades\Facade;

class Component extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\LivewireForms\Form\Component::class;
    }
}
