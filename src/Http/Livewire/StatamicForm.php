<?php

namespace Aerni\StatamicLivewireForms\Http\Livewire;

use Aerni\StatamicLivewireForms\Traits\FollowsRules;
use Aerni\StatamicLivewireForms\Traits\GetsFormFields;
use Aerni\StatamicLivewireForms\Traits\HandlesStatamicForm;
use Livewire\Component;
use Illuminate\Support\Str;

class StatamicForm extends Component
{
    use FollowsRules, GetsFormFields, HandlesStatamicForm;

    protected $form;

    public $formHandle;
    public $data;
    public $success;
    public $redirect;

    public function mount($form): void
    {
        $this->formHandle = $form;
        $this->form = $this->statamicForm();
        $this->data = $this->formProperties();
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

    protected function formProperties(): array
    {
        return
            $this->fields()->mapWithKeys(function ($field) {
                return [$field['handle'] => $this->assignFieldProperty($field)];
            })
            ->put($this->form->honeypot(), null)
            ->toArray();
    }

    protected function assignFieldProperty(array $field): ?array
    {
        if ($field['type'] === 'checkboxes') {
            return (count($field['options']) > 1) ? [] : null;
        }

        return null;
    }

    protected function validationAttributes(): array
    {
        return $this->fields()->mapWithKeys(function ($field) {
            return [$field['key'] => $field['label']];
        })->toArray();
    }

    public function submit(): void
    {
        $this->validate();
        $this->submitStatamicForm();
        $this->success();
    }

    protected function success()
    {
        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->data = $this->formProperties();
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.' . Str::slug($this->formHandle), [
            'fields' => $this->fields(),
            'honeypot' => $this->honeypot(),
        ]);
    }
}
