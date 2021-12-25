<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Aerni\LivewireForms\Form\Fields;
use Statamic\Facades\Form as StatamicForm;
use Aerni\LivewireForms\Traits\FollowsRules;
use Aerni\LivewireForms\Traits\HandlesStatamicForm;

class Form extends Component
{
    use FollowsRules, HandlesStatamicForm;

    public string $handle;
    public string $view;
    public array $data = [];

    public function mount(string $form, string $view = null): void
    {
        $this->handle = $form;
        $this->view = $view ?? Str::slug($this->handle);
        $this->data = $this->fields->defaultValues();
    }

    public function getFormProperty()
    {
        if (! $this->handle) {
            throw new \Exception('The form handle is missing. Please make sure to add it to the form tag.');
        }

        $form = StatamicForm::find($this->handle);

        if (! $form) {
            throw new \Exception("Form with handle [{$this->handle}] cannot be found.");
        }

        return $form;
    }

    public function getFieldsProperty(): Fields
    {
        return Fields::make($this->form, $this->id);
    }

    public function updated($field): void
    {
        $this->validateOnly($field, $this->realtimeRules($field));
    }

    public function submit(): void
    {
        $this->validate();
        $this->submitStatamicForm();
    }

    public function render()
    {
        return view('livewire/forms.' . $this->view, [
            'fields' => $this->fields->all(),
        ]);
    }
}
