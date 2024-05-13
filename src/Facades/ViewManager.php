<?php

namespace Aerni\LivewireForms\Facades;

use Illuminate\Support\Facades\Facade;

class ViewManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\LivewireForms\ViewManager::class;
    }
}
