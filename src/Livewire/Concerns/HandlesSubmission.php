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
    protected array $submittableFields = [];

    protected Submission $submission;

    protected function handleSubmission(): self
    {
        return $this
            ->updateSubmittableFields()
            ->makeSubmission()
            ->handleFormEvents()
            ->handleSubmissionEvents()
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

        $this->dispatch('formSubmitted');

        throw_if(FormSubmitted::dispatch($this->submission) === false, new SilentFormFailureException);

        return $this;
    }

    public function formSubmitted(Submission $submission): void
    {
        //
    }

    protected function handleSubmissionEvents(): self
    {
        $this->submissionCreating($this->submission);

        $this->dispatch('submissionCreated');

        if ($this->form->store()) {
            $this->submission->save();
        } else {
            /**
             * When the submission is saved, this same created event will be dispatched.
             * We'll also fire it here if submissions are not configured to be stored
             * so that developers may continue to listen and modify it as needed.
             */
            SubmissionCreated::dispatch($this->submission);
        }

        return $this;
    }

    public function submissionCreating(Submission $submission): void
    {
        //
    }

    protected function sendEmails(): self
    {
        SendEmails::dispatch($this->submission, Site::findByUrl(URL::previous()));

        return $this;
    }
}
