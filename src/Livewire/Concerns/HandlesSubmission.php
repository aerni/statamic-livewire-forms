<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Facades\URL;
use Statamic\Contracts\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Statamic\Events\SubmissionCreated;
use Statamic\Events\SubmissionCreating;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;

trait HandlesSubmission
{
    protected array $submittableFields = [];

    protected Submission $submission;

    protected function handleSubmission(): self
    {
        return $this
            ->updateSubmittableFields()
            ->makeSubmission()
            ->handleFormEvents()
            ->saveSubmission()
            ->sendEmails();
    }

    // TODO: Remove submittable on the field?
    protected function updateSubmittableFields(): self
    {
        collect($this->submittableFields)->each(fn ($submittable, $field) => $this->fields->get($field)->submittable($submittable));

        return $this;
    }

    protected function makeSubmission(): self
    {
        $submission = $this->form->makeSubmission();

        $processedValues = $this->form->blueprint()
            ->fields()
            ->addValues($this->processedValues()->all())
            ->process()
            ->values();

        $this->submission = $submission->data($processedValues);

        return $this;
    }

    protected function handleFormEvents(): self
    {
        $this->formSubmitting($this->submission);

        $this->dispatch('form-submitting', $this->submission);

        throw_if(FormSubmitted::dispatch($this->submission) === false, new SilentFormFailureException);

        $this->dispatch('form-submitted', $this->submission);

        return $this;
    }

    protected function saveSubmission(): self
    {
        $this->submissionCreating($this->submission);

        $this->dispatch('submission-creating', $this->submission);

        /**
         * When the submission is saved, the same events will be dispatched.
         * We'll also fire them here if submissions are not configured to be stored
         * so that developers may continue to listen and modify it as needed.
         */
        if ($this->form->store()) {
            $this->submission->save();
        } else {
            SubmissionCreating::dispatch($this->submission);
            SubmissionCreated::dispatch($this->submission);
        }

        $this->dispatch('submission-created', $this->submission);

        return $this;
    }

    protected function sendEmails(): self
    {
        SendEmails::dispatch($this->submission, Site::findByUrl(URL::previous()));

        return $this;
    }

    public function formSubmitted(Submission $submission): void
    {
        //
    }

    public function submissionCreating(Submission $submission): void
    {
        //
    }
}
