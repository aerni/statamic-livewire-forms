<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait WithView
{
    public string $view;

    public function mountWithView(): void
    {
        $this->view = $this->view();
    }

    protected function view(): string
    {
        // Try to get the view defined in a custom component.
        if (isset(static::$VIEW)) {
            return static::$VIEW;
        }

        // Try to get the view passed to the component in the view.
        if (isset($this->view)) {
            return $this->view;
        }

        // Try to autoload the view by form handle.
        if (view()->exists("livewire-forms::{$this->handle}")) {
            return $this->handle;
        }

        // Fall back to the default view.
        return config('livewire-forms.view', 'default');
    }
}
