<?php

namespace Aerni\StatamicLivewireForms\Http\Livewire;

use Aerni\StatamicLivewireForms\Traits\FollowsRules;
use Aerni\StatamicLivewireForms\Traits\HandlesStatamicForm;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

class StatamicForm extends Component
{
    use FollowsRules, HandlesStatamicForm;

    protected $form;

    public $data;
    public $handle;
    public $success;
    public $redirect;

    public function mount(): void
    {
        $this->form = $this->statamicForm();
        $this->data = $this->formProperties();
    }

    public function hydrate(): void
    {
        // Need this because $form is a protected property and doesn't persist between requests.
        $this->form = $this->statamicForm();
        $this->success = false;
    }

    public function updated($field): void
    {
        $this->validateOnly($field, $this->realtimeRules($field));
    }

    protected function formProperties(): Collection
    {
        return $this->fields()->mapWithKeys(function ($field) {
            $genericDefault = $field->type === 'checkboxes' ? [] : null;
            return [$field->handle => $field->default ?? $genericDefault];
        })->put($this->form->honeypot(), null);
    }

    protected function fields(): Collection
    {
        return $this->form->fields()
            ->map(function ($field) {
                return (object) [
                    'label' => $this->assignFieldLabel($field),
                    'handle' => $field->handle(),
                    'key' => 'data.' . $field->handle(),
                    'type' => $this->assignFieldType($field->get('type')),
                    'input_type' => $this->assignFieldInputType($field->get('type'), $field->get('input_type')),
                    'default' => $field->get('default'),
                    'placeholder' => $field->get('placeholder'),
                    'autocomplete' => $field->get('autocomplete'),
                    'width' => $field->get('width') ?? 100,
                    'rules' => collect($field->rules())->flatten()->toArray(),
                    'realtime' => $field->get('realtime'),
                ];
            });
    }

    protected function assignFieldLabel($field): string
    {
        if (Lang::has('statamic-livewire-forms::forms.' . $field->handle())) {
            return Lang::get('statamic-livewire-forms::forms.' . $field->handle());
        };

        return $field->get('display');
    }

    protected function assignFieldType(string $type): string
    {
        $types = [
            'text' => 'input',
            'textarea' => 'textarea',
            'integer' => 'input',
            'checkboxes' => 'checkbox',
            'radio' => 'radio',
            'select' => 'select',
            'assets' => 'file',
        ];

        return $types[$type] ?? 'input';
    }

    protected function assignFieldInputType(string $fieldType, ?string $intputType): string
    {
        $types = [
            'integer' => 'number',
        ];

        return $types[$fieldType] ?? $intputType;
    }

    protected function honeypot(): object
    {
        return (object) [
            'label' => Str::ucfirst($this->form->honeypot()),
            'handle' => $this->form->honeypot(),
            'key' => 'data.' . $this->form->honeypot(),
        ];
    }

    protected function validationAttributes(): array
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['data.' . $field->handle() => $field->display()];
        })->toArray();
    }

    public function submit(): void
    {
        $this->validate();
        $this->submitStatamicForm();
        $this->successResponse();
    }

    protected function successResponse()
    {
        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->data = $this->formProperties();
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.' . Str::slug($this->handle), [
            'fields' => $this->fields(),
            'honeypot' => $this->honeypot(),
        ]);
    }
}
