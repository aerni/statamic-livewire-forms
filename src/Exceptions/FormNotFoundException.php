<?php

namespace Aerni\LivewireForms\Exceptions;

use Exception;

class FormNotFoundException extends Exception
{
    public function __construct(protected string $handle)
    {
        parent::__construct("Form with handle [{$this->handle}] cannot be found.");
    }
}
