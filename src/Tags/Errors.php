<?php

namespace Aerni\StatamicLivewireForms\Tags;

use Statamic\Tags\Tags;

class Errors extends Tags
{
    protected static $handle = 'errors';

    public function index()
    {
        $errorBag = $this->context['errors']->getBag('default');

        if ($errorBag->isEmpty()) {
            return false;
        }

        foreach ($errorBag->all() as $error) {
            $errors[]['value'] = $error;
        }

        return ($this->content === '') // If this is a single tag ...
            ? true // ... just output a boolean.
            : $this->parseLoop($errors); // Otherwise, parse the content loop.
    }
}
