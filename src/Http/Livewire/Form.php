<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Aerni\LivewireForms\Traits\FollowsRules;
use Aerni\LivewireForms\Traits\GetsFormFields;
use Aerni\LivewireForms\Traits\HandlesStatamicForm;
use Aerni\LivewireForms\Traits\HydratesData;

class Form extends Component
{
    use FollowsRules, GetsFormFields, HandlesStatamicForm, HydratesData;

    protected $form;

    public $formHandle;
    public $view;
    public $data;
    public $success;
    public $redirect;

    public function mount($form, $view = null): void
    {
        $this->formHandle = $form;
        $this->view = $view ?? Str::slug($this->formHandle);
        $this->form = $this->statamicForm();
        $this->data = $this->hydrateData();
    }

    public function hydrate(): void
    {
        // Need this because $form is a protected property and doesn't persist between requests.
        $this->form = $this->statamicForm();

        // Reset success if the user keeps on interacting with the form after it has been submitted.
        $this->success = false;
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
            'fields' => $this->fields(),
        ]);
    }
}
