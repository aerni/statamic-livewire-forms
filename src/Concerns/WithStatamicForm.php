<?php

namespace Aerni\LivewireForms\Concerns;

use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Statamic\Forms\SendEmails;
use Illuminate\Support\Collection;
use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\URL;
use Statamic\Events\SubmissionCreated;
use Statamic\Contracts\Forms\Submission;
use Statamic\Forms\Form as StatamicForm;
use Statamic\Exceptions\SilentFormFailureException;
use Aerni\LivewireForms\Concerns\AllowDynamicFormFields;

trait WithStatamicForm
{
    protected Submission $formSubmission;

    public function getFormProperty(): StatamicForm
    {
        if ($this->uses(CreateStatamicForm::class)) {
            return $this->findOrMakeForm();
        }

        return Form::find($this->formHandle)
            ?? throw new \Exception("Form with handle [{$this->formHandle}] cannot be found.");
    }

    public function submitForm(): self
    {
        // Make sure we don't continue and save an empty form submission.
        if (empty($this->getFormData())) {
            return $this;
        }

        return $this
            ->validateForm()
            ->makeFormSubmission()
            ->handleFormEvents()
            ->storeFormSubmission();
    }

    protected function validateForm(): self
    {
        if ($rules = $this->getLivewireFormRules()) {
            $this->validate($rules);
        };

        if ($rules = $this->getStatamicFormRules()) {
            $this->validate($rules);
        };

        return $this;
    }

    protected function getLivewireFormRules(): array
    {
        return collect($this->getRules())
            ->intersectByKeys($this->getFormFields())
            ->all();
    }

    protected function getStatamicFormRules(): array
    {
        $fields = $this->getFormFields();

        return collect($this->form->blueprint()->fields()->validator()->rules())
            ->only($fields->values())
            ->mapWithKeys(fn ($rules, $field) => [$fields->flip()->get($field) => implode('|', $rules)])
            ->all();
    }

    // TODO: We are calling this so many times. Should probably cache it.
    protected function getFormFields(): Collection
    {
        $fields = Arr::isAssoc($this->formFields)
            ? collect($this->formFields)
            : collect($this->formFields)->mapWithKeys(fn ($field) => [$field => Str::afterLast($field, '.')]);

        if ($this->uses(AllowDynamicFormFields::class)) {
            return $fields;
        }

        return $fields->filter(fn ($field) => $this->form->blueprint()->hasField($field));
    }

    protected function getFormData(): array
    {
        return $this->getFormFields()
            ->mapWithKeys(fn ($field, $property) => [$field => $this->getPropertyValue($property)])
            ->filter()
            ->all();
    }

    protected function makeFormSubmission(): self
    {
        $this->formSubmission = $this->form->makeSubmission();

        // TODO: Add way to upload files.
        // $assetIds = $this->formSubmission->uploadFiles($this->uploadedFiles());

        // Process the data according to the blueprint fields.
        $data = $this->form->blueprint()->fields()
            ->addValues($this->getFormData())
            ->process()
            ->values()
            ->filter()
            ->all();

        if ($this->uses(AllowDynamicFormFields::class)) {
            $data = array_merge($this->getFormData(), $data);
        }

        $this->formSubmission->data($data);

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

    protected function uses(string $trait): bool
    {
        return in_array($trait, class_uses_recursive($this));
    }
}
