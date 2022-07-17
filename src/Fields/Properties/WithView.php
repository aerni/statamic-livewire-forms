<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Aerni\LivewireForms\Facades\Component;

trait WithView
{
    protected function viewProperty(): string
    {
        return Component::getView("fields.{$this->field->get('view', static::$view)}");
    }
}
