<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Aerni\LivewireForms\Form\Fields;
use Aerni\LivewireForms\Traits\FollowsRules;
use Aerni\LivewireForms\Traits\HandlesFormSubmission;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use FollowsRules, HandlesFormSubmission;

    public string $handle;
    public string $view;
    public array $data = [];

    public function mount(string $form, string $view = null): void
    {
        $this->handle = $form;
        $this->view = $view ?? Str::slug($form);
        $this->data = $this->fields->defaultValues();
    }

    public function getFormProperty(): \Statamic\Forms\Form
    {
        return \Statamic\Facades\Form::find($this->handle)
            ?? throw new \Exception("Form with handle [{$this->handle}] cannot be found.");
    }

    public function getFieldsProperty(): Fields
    {
        return Fields::make($this->form, $this->id, $this->data);
    }

    public function render(): View
    {
        return view("livewire/forms.{$this->view}", [
            'fields' => $this->fields->all(),
            'honeypot' => $this->fields->honeypot(),
        ]);
    }
}
