<?php

namespace Aerni\LivewireForms\Traits;

use Statamic\Support\Str;
use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Illuminate\Support\Facades\URL;
use Statamic\Events\SubmissionCreated;
use Statamic\Forms\Form as StatamicForm;

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
        if ($this->isSpam()) {
            return;
        }

        $data = $this->normalizeData($this->data);

        $submission = $this->form->makeSubmission()->data($data);

        if ($this->form->store()) {
            $submission->save();
        }

        $site = Site::findByUrl(URL::previous());

        SubmissionCreated::dispatch($submission);
        SendEmails::dispatch($submission, $site);
    }

    protected function isSpam(): bool
    {
        return (bool) $this->data[$this->form->honeypot()];
    }
}
