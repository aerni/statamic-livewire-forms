<?php

namespace Aerni\LivewireForms\Facades;

use Illuminate\Support\Facades\Facade;

class Models extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\LivewireForms\Form\Models::class;
    }
}
