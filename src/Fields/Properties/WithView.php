<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithView
{
    protected function viewProperty(): string
    {
        // Try to load a user-defined view first.
        if ($this->field->get('view')) {
            return 'fields'.$this->field->get('view');
        }

        // Try to autoload the view by field handle.
        if (view()->exists("livewire-forms::fields.{$this->handle}")) {
            return 'fields'.$this->handle;
        }

        // Fall back to the default field view.
        return 'fields.'.static::$view;
    }
}
