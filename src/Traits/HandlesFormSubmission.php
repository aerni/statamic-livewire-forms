<?php

namespace Aerni\LivewireForms\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Statamic\Events\FormSubmitted;
use Statamic\Events\SubmissionCreated;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Statamic\Support\Str;

trait HandlesFormSubmission
{
    public function submit(): void
    {
        $this->validate();
        $this->handleFormSubmission();
    }

    protected function handleFormSubmission(): void
    {
        $data = $this->normalizeDataForSubmission($this->data);
        $submission = $this->form->makeSubmission()->data($data);

        try {
            if ($this->isSpam()) {
                throw new SilentFormFailureException;
            }

            $this->emit('formSubmitted');

            if (FormSubmitted::dispatch($submission) === false) {
                throw new SilentFormFailureException;
            }
        } catch (SilentFormFailureException $e) {
            $this->success();
            return;
        }

        if ($this->form->store()) {
            $submission->save();
        }

        $this->emit('submissionCreated');
        SubmissionCreated::dispatch($submission);

        $site = Site::findByUrl(URL::previous());
        SendEmails::dispatch($submission, $site);

        $this->success();
    }

    protected function normalizeDataForSubmission(array $data): array
    {
        return collect($data)->map(function ($value, $key) {
            $field = $this->fields->get($key);

            // We want to return nothing if the field can't be found (e.g. honeypot).
            if (is_null($field)) {
                return null;
            }

            // We don't want to submit the captcha value.
            if ($field['type'] === 'captcha') {
                return null;
            }

            if ($field['cast_booleans']) {
                return Str::toBool($value);
            }

            if ($field['input_type'] === 'number') {
                return (int) $value;
            }

            return $value;
        })->filter()->toArray();
    }

    protected function isSpam(): bool
    {
        return (bool) Arr::get($this->data, $this->form->honeypot());
    }

    protected function success(): void
    {
        /**
         * Merge the current data with the default values instead of resetting it.
         * This way we can preserve the captch and honeypot data.
         */
        $this->data = array_merge($this->data, $this->fields->defaultValues());

        session()->flash('success');
    }
}
