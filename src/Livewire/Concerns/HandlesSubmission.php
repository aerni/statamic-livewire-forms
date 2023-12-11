<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Facades\URL;
use Statamic\Contracts\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Statamic\Events\SubmissionCreated;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;

trait HandlesSubmission
{
    protected Submission $submission;

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

        $processedValues = $this->form->blueprint()
            ->fields()
            ->addValues($this->processedValues()->all())
            ->process()
            ->values();

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
