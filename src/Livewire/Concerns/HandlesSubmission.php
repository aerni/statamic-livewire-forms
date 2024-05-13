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
    protected Submission $submission;

    protected function handleSubmission(): self
    {
        return $this
            ->makeSubmission()
            ->handleFormEvents()
            ->saveSubmission()
            ->sendEmails();
    }

    protected function makeSubmission(): self
    {
        $this->submission = $this->form->makeSubmission()->data($this->processedValues());

        return $this;
    }

    protected function handleFormEvents(): self
    {
        $this->formSubmitted($this->submission);

        $this->dispatch('form-submitted', data: $this->submission->data());

        throw_if(FormSubmitted::dispatch($this->submission) === false, new SilentFormFailureException);

        return $this;
    }

    protected function saveSubmission(): self
    {
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
}
