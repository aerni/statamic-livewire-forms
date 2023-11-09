<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Facades\Component as FormComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;

class DynamicForm extends Component
{
    public string $component;

    public string $handle;

    public string $view;

    public string $theme;

    public function mount(): void
    {
        $this->handle = $this->handle ?? throw new \Exception('Please set the handle of the form you want to use.');
        $this->component = $this->getComponent();
        $this->view = $this->getView();
        $this->theme = $this->getTheme();
    }

    protected function getComponent(): string
    {
        $component = Str::replace('_', '-', $this->handle).'-form';

        return Livewire::isDiscoverable($component) ? $component : 'default-form';
    }

    protected function getView(): string
    {
        // Load the user-defined view if it exists
        if ($this->view ?? null) {
            return $this->view;
        }

        // Autoload the view by form handle if it exists
        if (view()->exists("livewire-forms::{$this->handle}")) {
            return $this->handle;
        }

        // Fall back to the default view
        return FormComponent::defaultView();
    }

    protected function getTheme(): string
    {
        return $this->theme ?? FormComponent::defaultTheme();
    }

    public function render(): View
    {
        return view('livewire-forms::dynamic-form');
    }
}
