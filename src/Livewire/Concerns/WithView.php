<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Facades\ViewManager;
use Livewire\Attributes\Locked;

trait WithView
{
    #[Locked]
    public string $view;

    public function mountWithView(): void
    {
        $this->view = $this->view();
    }

    protected function view(): string
    {
        /* Get the view passed to the component in the view. */
        if (isset($this->view)) {
            return $this->view;
        }

        /* Use the view configured in the form config if it exists. */
        if (ViewManager::viewExists($this->form->view)) {
            return $this->form->view;
        }

        /* Autoload the view by form handle if it exists. */
        if (ViewManager::viewExists($this->handle)) {
            return $this->handle;
        }

        /* Fall back to the default view. */
        return ViewManager::defaultView();
    }
}
