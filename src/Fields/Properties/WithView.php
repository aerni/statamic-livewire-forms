<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Aerni\LivewireForms\Facades\Component;

trait WithView
{
    protected function viewProperty(): string
    {
        $auto = Component::getView("fields.{$this->handle}");
        $field = Component::getView("fields.{$this->field->get('view')}");
        $default = Component::getView('fields.'.static::$view);

        return match (true) {
            view()->exists($field) => $field,
            view()->exists($auto) => $auto,
            default => $default
        };
    }
}
