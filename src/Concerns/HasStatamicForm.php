<?php

namespace Aerni\LivewireForms\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Statamic\Contracts\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Statamic\Events\SubmissionCreated;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Facades\Site;
use Statamic\Forms\Form;
use Statamic\Forms\SendEmails;

trait HasStatamicForm
{
    protected Submission $formSubmission;

    public function submitForm(): self
    {
        return $this
            ->validateForm()
            ->makeFormSubmission()
            ->handleFormEvents()
            ->storeFormSubmission();
    }

    public function getFormProperty(): Form
    {
        return \Statamic\Facades\Form::find($this->formHandle)
            ?? throw new \Exception("Form with handle [{$this->formHandle}] cannot be found.");
    }

    protected function validateForm(): self
    {
        $this->form->blueprint()->fields()
            ->addValues($this->formData())
            ->validate($this->formRules());

        return $this;
    }

    protected function formRules(): array
    {
        if (Arr::isAssoc($this->formFields)) {
            return collect($this->getRules())
                ->only(array_keys($this->formFields))
                ->mapWithKeys(fn ($rules, $field) => [$this->formFields[$field] => $rules])
                ->all();
        }

        return collect($this->getRules())
            ->only($this->formFields)
            ->mapWithKeys(fn ($rules, $field) => [Str::afterLast($field, '.') => $rules])
            ->all();
    }

    protected function formData(): array
    {
        if (Arr::isAssoc($this->formFields)) {
            return collect($this->formFields)
                ->mapWithKeys(fn ($field, $property) => [$field => $this->getPropertyValue($property)])
                ->all();
        }

        return collect($this->formFields)
            ->mapWithKeys(fn ($property) => [Str::afterLast($property, '.') => $this->getPropertyValue($property)])
            ->all();
    }

    protected function makeFormSubmission(): self
    {
        $this->formSubmission = $this->form->makeSubmission();

        // Add way to upload files.
        // $assetIds = $this->formSubmission->uploadFiles($this->uploadedFiles());

        // Should we normalize data? Or should this be up to the user?
        // $values = array_merge($this->normalizedData(), $assetIds);

        $processedData = $this->form->blueprint()->fields()
            ->addValues($this->formData())
            ->process()->values();

        $this->formSubmission->data($processedData);

        return $this;
    }

    protected function handleFormEvents(): self
    {
        $formSubmitted = FormSubmitted::dispatch($this->formSubmission);

        if ($formSubmitted === false) {
            throw new SilentFormFailureException();
        }

        SubmissionCreated::dispatch($this->formSubmission);

        SendEmails::dispatch($this->formSubmission, Site::findByUrl(URL::previous()));

        return $this;
    }

    protected function storeFormSubmission(): self
    {
        if ($this->form->store()) {
            $this->formSubmission->save();
        }

        return $this;
    }
}
