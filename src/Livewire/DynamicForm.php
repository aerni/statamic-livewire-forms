<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Facades\Component as FormComponent;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DynamicForm extends Component
{
    public string $component;

    public string $handle;

    public string $view;

    public string $theme;

    public function mount(): void
    {
        $this->handle = $this->handle ?? throw new \Exception('Please set the handle of the form you want to use.');
        $this->component = FormComponent::getComponent($this->handle);
        $this->view = $this->view ?? FormComponent::defaultView();
        $this->theme = $this->theme ?? FormComponent::defaultTheme();
    }

    public function render(): View
    {
        return view('livewire-forms::dynamic-form');
    }
}
