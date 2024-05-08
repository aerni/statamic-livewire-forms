<?php

namespace Aerni\LivewireForms\Exceptions;

use Exception;

class StepDoesNotExist extends Exception
{
    public static function stepNotFound(string $step): self
    {
        return new static("Step {$step} does not exist.");
    }

    public static function stepIsInvisible(string $step): self
    {
        return new static("Step {$step} is invisible and can't be shown.");
    }

    public static function noPreviousStep(string $step): self
    {
        return new static("Step {$step} requested to go to the previous step, but there is no previous step.");
    }

    public static function noNextStep(string $step): self
    {
        return new static("Step {$step} requested to go to the next step, but there is no next step.");
    }
}
