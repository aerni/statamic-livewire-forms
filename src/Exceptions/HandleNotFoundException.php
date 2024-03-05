<?php

namespace Aerni\LivewireForms\Exceptions;

use Exception;

class HandleNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('You need to set the handle of the form you want to use.');
    }
}
