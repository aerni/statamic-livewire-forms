<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Aerni\LivewireForms\Facades\ViewManager;

trait WithView
{
    protected function viewProperty(): string
    {
        $configViewName = "fields.{$this->field->get('view')}";
        $handleViewName = "fields.{$this->handle}";

        $configView = ViewManager::themeViewPath($this->component->theme, $configViewName);
        $handleView = ViewManager::themeViewPath($this->component->theme, $handleViewName);

        return match (true) {
            view()->exists($configView) => $configViewName, // Try to load a user-defined view first.
            view()->exists($handleView) => $handleViewName, // Try to autoload the view by field handle.
            default => "fields.{$this->view}" // Fall back to the default field view.
        };
    }
}
