<?php

namespace Aerni\LivewireForms\Fields\Properties;

use Livewire\Livewire;
use Aerni\LivewireForms\Facades\ViewManager;

trait WithView
{
    protected function viewProperty(?string $view = null): string
    {
        $overrideView = "fields.{$view}";
        $configView = "fields.{$this->field->get('view')}";
        $handleView = "fields.{$this->handle}";

        return match (true) {
            $this->viewExists($overrideView) => $overrideView, // Load a view defined in the component.
            $this->viewExists($configView) => $configView, // Load a view defined in the config.
            $this->viewExists($handleView) => $handleView, // Autoload the view by field handle.
            default => "fields/{$this->view}" // Fall back to the default field view.
        };
    }

    protected function viewExists(string $view): bool
    {
        return ViewManager::themeViewExists(Livewire::current()->theme, $view);
    }
}
