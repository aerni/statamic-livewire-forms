<?php

namespace Aerni\LivewireForms\Traits;

use Statamic\Support\Str;
use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\URL;
use Statamic\Events\SubmissionCreated;
use Statamic\Forms\Form as StatamicForm;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Forms\Submission;

trait HandlesStatamicForm
{
    protected function statamicForm(): StatamicForm
    {
        if (! $this->formHandle) {
            throw new \Exception('The form handle is missing. Please make sure to add it to the form tag.');
        }

        $form = Form::find($this->formHandle);

        if (! $form) {
            throw new \Exception("Form with handle [{$this->formHandle}] cannot be found.");
        }

        return $form;
    }

    protected function normalizeData(array $data): array
    {
        return collect($data)->map(function ($value, $key) {
            $field = collect($this->fields()->get($key));

            if ($field->get('cast_booleans')) {
                return Str::toBool($value);
            }

            if ($field->get('input_type') === 'number') {
                return (int) $value;
            }

            return $value;
        })->toArray();
    }

    protected function getFormSubmission(): Submission
    {
        return $this->form->makeSubmission()
            ->data($this->normalizeData($this->data));
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
        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->data = $this->hydrateFormData();
        $this->success = true;
    }

    protected function isSpam(): bool
    {
        return (bool) $this->data[$this->form->honeypot()];
    }
}
