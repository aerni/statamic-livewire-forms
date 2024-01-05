<?php

namespace Aerni\LivewireForms\Exceptions;

use Exception;

class ProtectedPropertyException extends Exception
{
    public function __construct(protected string $property)
    {
        parent::__construct("The property [{$this->property}] is protected and cannot be changed.");
    }
}
