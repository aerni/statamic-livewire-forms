<?php

namespace Aerni\LivewireForms\Exceptions;

use Exception;

class FormHasNoFieldsException extends Exception
{
    public function __construct(protected string $handle)
    {
        parent::__construct("The form [{$this->handle}] has no fields.");
    }
}
