<?php

namespace Aerni\LivewireForms\Exceptions;

use Exception;

class StepNotFoundException extends Exception
{
    public function __construct(protected string $from, protected string $to)
    {
        parent::__construct("The wizard requested to go from step {$this->from} to step {$this->to}, but step {$this->to} doesn't exist.");
    }
}
