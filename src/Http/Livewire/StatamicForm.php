<?php

namespace Aerni\StatamicLivewireForms\Http\Livewire;

use Livewire\Component;
use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Statamic\Events\SubmissionCreated;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class StatamicForm extends Component
{
    protected $form;

    public $data;
    public $handle;
    public $success;
    public $redirect;
    public $honeypot;

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

    protected function statamicForm()
    {
        if (! $this->handle) {
            throw new \Exception('The form handle is missing. Please make sure to add it to the form tag.');
        }

        $form = Form::find($this->handle);

        if (! $form) {
            throw new \Exception("Form with handle [{$this->handle}] cannot be found.");
        }

        return $form;
    }

    protected function formProperties(): Collection
    {
        return $this->fields()->mapWithKeys(function ($field) {
            $genericDefault = $field->type === 'checkboxes' ? [] : null;
            return [$field->handle => $field->default ?? $genericDefault];
        });
    }

    protected function fields(): Collection
    {
        return $this->form->fields()
            ->map(function ($field) {
                return (object) [
                    'label' => $field->get('display'),
                    'handle' => $field->handle(),
                    'key' => 'data.' . $field->handle(),
                    'type' => $this->assignFieldType($field->get('type')),
                    'input_type' => $field->get('input_type'),
                    'default' => $field->get('default'),
                    'placeholder' => $field->get('placeholder'),
                    'autocomplete' => $field->get('autocomplete'),
                ];
            });
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

    protected function rules(): array
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['data.' . $field->handle() => collect($field->rules())->flatten()];
        })->toArray();
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
        $this->handleFormSubmission();
        $this->successResponse();
    }

    protected function handleFormSubmission(): void
    {
        if ($this->honeypot) {
            return;
        };

        $submission = $this->form->makeSubmission()->data($this->data);

        if ($this->form->store()) {
            $submission->save();
        }

        $site = Site::findByUrl(URL::previous());

        SubmissionCreated::dispatch($submission);
        SendEmails::dispatch($submission, $site);
    }

    protected function successResponse()
    {
        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->data = $this->formProperties();
        $this->honeypot = null;
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.' . Str::slug($this->handle), [
            'fields' => $this->fields()
        ]);
    }
}
