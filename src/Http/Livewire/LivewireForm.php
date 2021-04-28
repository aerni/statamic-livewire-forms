<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Statamic\Events\SubmissionCreated;
use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;

class LivewireForm extends Component
{
    public $handle;
    public $view;
    public $fields;
    public $success;
    public $field;

    protected $form;

    public function mount($handle, $view = null)
    {
        $this->handle = $handle;
        $this->view = $view ?? Str::slug($this->handle);
        $this->getForm();
        $this->fields = array_fill_keys($this->form->blueprint()->fields()->all()->keys()->toArray(), '');
        dd($this->getFields());
    }

    protected function getFields()
    {
        return $this->form->fields()
            ->map(function ($field) {
                return $this->getRenderableField($field, "form.{$this->handle}");
            })
            ->values()
            ->all();
    }

    protected function getRenderableField($field, $errorBag = 'default')
    {
        $errors = session('errors') ? session('errors')->getBag($errorBag) : new MessageBag;

        $data = array_merge($field->toArray(), [
            'error' => $errors->first($field->handle()) ?: null,
            'old' => old($field->handle()),
        ]);

        $data['field'] = view($field->fieldtype()->view(), $data);

        return $data;
    }

    public function hydrate()
    {
        $this->getForm();
    }

    private function getForm()
    {
        $this->form = Form::find($this->handle);
    }

    protected function rules()
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['fields.' . $field->handle() => collect($field->rules())->flatten()];
        })->toArray();
    }

    protected function validationAttributes()
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['fields.' . $field->handle() => $field->display()];
        })->toArray();
    }

    public function submit()
    {
        $site = Site::findByUrl(URL::previous());

        $validatedData = $this->validate();

        $submission = $this->form->makeSubmission()->data($validatedData['fields']);

        if ($this->form->store()) {
            $submission->save();
        }

        SubmissionCreated::dispatch($submission);
        SendEmails::dispatch($submission, $site);

        $this->reset('fields');
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.' . $this->view);
    }
}
