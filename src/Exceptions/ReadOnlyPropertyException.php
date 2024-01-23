<?php

namespace Aerni\LivewireForms\Exceptions;

use Exception;

class ReadOnlyPropertyException extends Exception
{
    public function __construct(protected string $property)
    {
        parent::__construct("The [{$this->property}] property is read-only and cannot be changed.");
    }
}
