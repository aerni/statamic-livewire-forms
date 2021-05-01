<?php

namespace Aerni\StatamicLivewireForms\Tags;

use Statamic\Tags\Tags;

class Errors extends Tags
{
    protected static $handle = 'errors';

    public function any(): bool
    {
        return $this->context['errors']->getBag('default')->isNotEmpty();
    }

    public function all(): string
    {
        $errorBag = $this->context['errors']->getBag('default');

        foreach ($errorBag->all() as $error) {
            $errors[]['error'] = $error;
        }

        return $this->parseLoop($errors);
    }
}
