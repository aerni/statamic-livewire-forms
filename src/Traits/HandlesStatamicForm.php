<?php

namespace Aerni\LivewireForms\Traits;

use Statamic\Support\Str;
use Statamic\Facades\Site;
use Illuminate\Support\Arr;
use Statamic\Forms\SendEmails;
use Statamic\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\URL;
use Statamic\Events\SubmissionCreated;
use Statamic\Exceptions\SilentFormFailureException;

trait HandlesStatamicForm
{
    protected function normalizeDataForSubmission(array $data): array
    {
        return collect($data)->map(function ($value, $key) {
            $field = collect($this->fields->get($key));

            // We don't want to submit the honeypot value.
            if ($field->get('type') === 'honeypot') {
                return null;
            }

            // We don't want to submit the captcha value.
            if ($field->get('type') === 'captcha') {
                return null;
            }

            if ($field->get('cast_booleans')) {
                return Str::toBool($value);
            }

            if ($field->get('input_type') === 'number') {
                return (int) $value;
            }

            return $value;
        })->filter()->toArray();
    }

    protected function getFormSubmission(): Submission
    {
        return $this->form->makeSubmission()
            ->data($this->normalizeDataForSubmission($this->data));
    }

    protected function submitStatamicForm()
    {
        $submission = $this->getFormSubmission();

        try {
            if ($this->isSpam()) {
                throw new SilentFormFailureException;
            }

            $this->emit('formSubmitted');

            if (FormSubmitted::dispatch($submission) === false) {
                throw new SilentFormFailureException;
            }
        } catch (SilentFormFailureException $e) {
            return $this->success();
        }

        if ($this->form->store()) {
            $submission->save();
        }

        $this->emit('submissionCreated');
        SubmissionCreated::dispatch($submission);

        $site = Site::findByUrl(URL::previous());
        SendEmails::dispatch($submission, $site);

        return $this->success();
    }

    protected function success()
    {
        /**
         * Merge the current data with the default values instead of resetting it.
         * This way we can preserve the captch and honeypot data.
         */
        $this->data = array_merge($this->data, $this->fields->defaultValues());

        session()->flash('success');
    }

    protected function isSpam(): bool
    {
        return (bool) Arr::get($this->data, $this->form->honeypot());
    }
}
