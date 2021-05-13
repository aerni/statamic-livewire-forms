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

    protected function submitStatamicForm(): void
    {
        // Throw a silent failure if a bot filled the honeypot.
        if ($this->isSpam()) {
            throw new SilentFormFailureException;
            // TODO: Return success even when its Spam to trick bots.
        }

        $data = $this->normalizeData($this->data);
        $submission = $this->form->makeSubmission()->data($data);

        $this->emit('formSubmitted');
        $formSubmittedEvent = FormSubmitted::dispatch($submission);

        // Throw a silent failure if an event listener returns false.
        if ($formSubmittedEvent === false) {
            throw new SilentFormFailureException;
        }

        if ($this->form->store()) {
            $submission->save();
        }

        $site = Site::findByUrl(URL::previous());

        $this->emit('submissionCreated');
        SubmissionCreated::dispatch($submission);

        SendEmails::dispatch($submission, $site);
    }

    protected function isSpam(): bool
    {
        return (bool) $this->data[$this->form->honeypot()];
    }
}
