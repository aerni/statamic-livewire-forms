<?php

namespace Aerni\LivewireForms\Tags;

use Statamic\Tags\Tags;

class LivewireForms extends Tags
{
    public function errors()
    {
        if (! $this->hasErrors()) {
            return false;
        }

        $errors = [];

        foreach ($this->context['_instance']->getErrorBag()->all() as $error) {
            $errors[]['error'] = $error;
        }

        return $this->isPair    // If this is a tag pair...
            ? $errors           // return the errors
            : ! empty($errors); // Otherwise, just output a boolean.
    }

    public function errorsCount(): int
    {
        return $this->context['_instance']->getErrorBag()->count();
    }

    private function hasErrors(): bool
    {
        return $this->context['_instance']->getErrorBag()->isNotEmpty();
    }
}
