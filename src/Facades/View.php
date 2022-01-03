<?php

namespace Aerni\LivewireForms\Facades;

use Illuminate\Support\Facades\Facade;

class View extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\LivewireForms\Form\View::class;
    }
}
