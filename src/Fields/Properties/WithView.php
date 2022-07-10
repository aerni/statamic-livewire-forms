<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Aerni\LivewireForms\Facades\Component;

trait WithView
{
    public function viewProperty(): string
    {
        return Component::getView("fields.{$this->field->get('view', static::VIEW)}");
    }
}
