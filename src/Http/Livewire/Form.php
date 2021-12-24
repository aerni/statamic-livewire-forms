<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Aerni\LivewireForms\Form\Fields;
use Statamic\Forms\Form as StatamicForm;
use Aerni\LivewireForms\Traits\FollowsRules;
use Aerni\LivewireForms\Traits\HydratesData;
use Aerni\LivewireForms\Traits\GetsFormFields;
use Aerni\LivewireForms\Traits\HandlesStatamicForm;

class Form extends Component
{
    use FollowsRules, HandlesStatamicForm, HydratesData;

    protected StatamicForm $form;
    public Collection $fields;

    public string $formHandle;
    public string $view;
    public array $data = [];

    public bool $success = false;
    public bool $redirect = false;

    public function mount(string $form, string $view = null): void
    {
        $this->formHandle = $form;
        $this->view = $view ?? Str::slug($this->formHandle);
        $this->form = $this->statamicForm();
        $this->fields = Fields::make($this->form);
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
            'fields' => $this->fields,
        ]);
    }
}
