<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Collection;
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
        $this->updateSubmittableFields();

        $this->submittingForm($this->fields);

        $this->submission = $this->makeSubmission();

        $this->createdSubmission($this->submission);

        $this->handleSubmissionEvents();

        $this->saveSubmission();

        $this->submittedForm($this->submission);

        return $this;
    }

    protected function updateSubmittableFields(): void
    {
        collect($this->submittableFields)->each(fn ($submittable, $field) => $this->fields->get($field)->submittable($submittable));
    }

    public function submittingForm(Collection $fields): void
    {
        //
    }

    protected function makeSubmission(): Submission
    {
        $submission = $this->form->makeSubmission();

        $processedValues = $this->form->blueprint()
            ->fields()
            ->addValues($this->processedValues()->all())
            ->process()
            ->values();

        return $submission->data($processedValues);
    }

    public function createdSubmission(Submission $submission): void
    {
        //
    }

    // TODO: Refactor this?
    protected function handleSubmissionEvents(): void
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
    }

    protected function saveSubmission(): void
    {
        if ($this->form->store()) {
            $this->submission->save();
        }
    }

    public function submittedForm(Submission $submission): void
    {
        //
    }
}
