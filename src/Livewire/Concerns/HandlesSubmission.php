<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Statamic\Facades\Site;
use Livewire\Attributes\On;
use Statamic\Forms\SendEmails;
use Illuminate\Support\Collection;
use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\URL;
use Statamic\Events\SubmissionCreated;
use Statamic\Contracts\Forms\Submission;
use Statamic\Exceptions\SilentFormFailureException;

trait HandlesSubmission
{
    protected Submission $submission;

    // TODO: Can we make this protected?
    public Collection $fieldsToSubmit;

    public function mountHandlesSubmission(): void
    {
        $this->fieldsToSubmit = collect();
    }

    #[On('field-conditions-updated')]
    public function submitFieldValue(string $field, bool $passesConditions): void
    {
        $this->fields->get($field)->always_save
            ? $this->fieldsToSubmit->put($field, true)
            : $this->fieldsToSubmit->put($field, $passesConditions);
    }

    protected function handleSubmission(): self
    {
        return $this
            ->runSubmittingFormHook()
            ->makeSubmission()
            ->runCreatedSubmissionFormHook()
            ->handleSubmissionEvents()
            ->storeSubmission()
            ->runSubmittedFormHook();
    }

    protected function runSubmittingFormHook(): self
    {
        $this->submittingForm();

        return $this;
    }

    protected function submittingForm(): void
    {
        //
    }

    protected function makeSubmission(): self
    {
        $this->submission = $this->form->makeSubmission();

        $assetIds = $this->submission->uploadFiles($this->uploadedFiles());

        $values = array_merge($this->normalizedDataForSubmission(), $assetIds);

        $processedValues = $this->form->blueprint()->fields()->addValues($values)->process()->values();

        $this->submission->data($processedValues);

        return $this;
    }

    protected function runCreatedSubmissionFormHook(): self
    {
        $this->createdSubmission($this->submission);

        return $this;
    }

    protected function createdSubmission(Submission $submission): void
    {
        //
    }

    protected function handleSubmissionEvents(): self
    {
        $this->dispatch('formSubmitted');
        $formSubmitted = FormSubmitted::dispatch($this->submission);

        if ($formSubmitted === false) {
            throw new SilentFormFailureException();
        }

        $this->dispatch('submissionCreated');
        SubmissionCreated::dispatch($this->submission);

        $site = Site::findByUrl(URL::previous()); // TODO: Does URL::previous still work in Livewire 3?
        SendEmails::dispatch($this->submission, $site);

        return $this;
    }

    protected function storeSubmission(): self
    {
        if ($this->form->store()) {
            $this->submission->save();
        }

        return $this;
    }

    protected function runSubmittedFormHook(): self
    {
        $this->submittedForm();

        return $this;
    }

    protected function submittedForm(): void
    {
        //
    }
}
