<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Livewire\Component;
use Statamic\Fields\Field;
use Illuminate\Support\Str;
use Aerni\LivewireForms\Traits\FollowsRules;
use Aerni\LivewireForms\Traits\GetsFormFields;
use Aerni\LivewireForms\Traits\HandlesStatamicForm;

class Form extends Component
{
    use FollowsRules, GetsFormFields, HandlesStatamicForm;

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
        $this->data = $this->hydrateFormData();
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

    protected function hydrateFormData(): array
    {
        return $this->form->fields()->mapWithKeys(function ($field) {
            return [$field->handle() => $this->assignDefaultFieldValue($field)];
        })
        ->put($this->form->honeypot(), null)
        ->toArray();
    }

    protected function assignDefaultFieldValue(Field $field)
    {
        if ($field->type() === 'checkboxes') {
            return $this->getDefaultCheckboxValue($field);
        }

        if ($field->type() === 'select') {
            return $this->getDefaultSelectValue($field);
        }

        /**
         * Make sure to always return the first array value if someone set the default value
         * to an array instead of a string or integer.
        */
        return array_first((array) $field->defaultValue());
    }

    protected function getDefaultCheckboxValue(Field $field)
    {
        $default = $field->defaultValue();
        $options = $field->get('options');

        return (count($options) > 1)
            ? (array) $default
            : array_first((array) $default);
    }

    protected function getDefaultSelectValue(Field $field): string
    {
        $default = $field->defaultValue();
        $options = $field->get('options');

        return $default ?? array_key_first($options);
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
    }

    public function render()
    {
        return view('livewire/forms.' . $this->view, [
            'fields' => $this->fields(),
            'honeypot' => $this->honeypot(),
        ]);
    }
}
