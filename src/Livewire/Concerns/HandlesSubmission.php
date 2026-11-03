<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Assets;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Statamic\Contracts\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Statamic\Events\SubmissionCreated;
use Statamic\Events\SubmissionCreating;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Statamic\Forms\SubmitForm;

trait HandlesSubmission
{
    protected ?Submission $submission = null;

    protected function handleSubmission(): self
    {
        /**
         * Statamic versions that ship the SubmitForm action handle spam, restrictions,
         * events, file uploads, and emails in the action itself. Older versions rely on
         * the addon's own submission flow below. This lets us support both versions
         * without a breaking release. The old flow can be dropped once this addon
         * requires a Statamic version that ships the action.
         */
        if (class_exists(SubmitForm::class)) {
            return $this->submitForm();
        }

        return $this
            ->handleSpam()
            ->makeSubmission()
            ->handleFormEvents()
            ->saveSubmission()
            ->sendEmails();
    }

    /**
     * Submit the form using Statamic's own action so that features like
     * spam protection, submission restrictions, emails, and file uploads
     * behave exactly like a regular Statamic form submission.
     */
    protected function submitForm(): self
    {
        [$files, $values] = $this->fields
            ->filter(fn ($field) => $this->submittableFields[$field->handle])
            ->partition(fn ($field) => $field instanceof Assets);

        $action = app(SubmitForm::class)->form($this->form);

        /**
         * Livewire can batch multiple component calls into a single request. Only react
         * to the submission of this component's action so that forms submitted in the
         * same request don't cross-fire each other's hooks and events.
         */
        Event::listen(FormSubmitted::class, function (FormSubmitted $event) use ($action) {
            if ($event->submission !== $action->submission()) {
                return;
            }

            $this->formSubmitted($event->submission);
            $this->dispatch('form-submitted', data: $event->submission->data());
        });

        try {
            $this->submission = $action->submit(
                data: $values->map(fn ($field) => $field->value())->all(),
                files: $files->map(fn ($field) => $field->value())->filter()->all(),
            )->submission;
        } catch (ValidationException $exception) {
            throw $this->validationExceptionWithFieldKeys($exception);
        } catch (SilentFormFailureException $exception) {
            /* Keep the submission out of the session when the honeypot caught spam. */
            if (! $this->isSpam()) {
                $this->submission = $action->submission();
            }

            throw $exception;
        }

        return $this;
    }

    protected function validationExceptionWithFieldKeys(ValidationException $exception): ValidationException
    {
        return ValidationException::withMessages(
            collect($exception->errors())
                ->mapWithKeys(function ($messages, $key) {
                    if (! $this->fields->has(Str::before($key, '.'))) {
                        return [$key => $messages];
                    }

                    return [preg_replace('/^([^.]+)/', 'fields.$1.value', $key) => $messages];
                })
                ->all()
        );
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
