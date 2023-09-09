<?php

namespace Aerni\LivewireForms\Livewire;

use Aerni\LivewireForms\Form\Component as FormComponent;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Form extends Component
{
    public string $handle;

    public string $component;

    public string $view;

    public string $theme;

    public function mount(): void
    {
        $this->handle = $this->handle ?? throw new \Exception('Please set the handle of the form you want to use.');
        $this->component = $this->formComponent->getComponent($this->handle);
        $this->view = $this->view ?? $this->formComponent->defaultView();
        $this->theme = $this->theme ?? $this->formComponent->defaultTheme();
    }

    public function getFormComponentProperty(): FormComponent
    {
        return \Aerni\LivewireForms\Facades\Component::getFacadeRoot();
    }

    public function render(): View
    {
        return view('livewire-forms::form');
    }
}
