<?php

namespace Aerni\StatamicLivewireForms\Traits;

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
        if (! $this->handle) {
            throw new \Exception('The form handle is missing. Please make sure to add it to the form tag.');
        }

        $form = Form::find($this->handle);

        if (! $form) {
            throw new \Exception("Form with handle [{$this->handle}] cannot be found.");
        }

        return $form;
    }

    protected function submitStatamicForm(): void
    {
        if ($this->isSpam()) {
            return;
        }

        $submission = $this->form->makeSubmission()->data($this->data);

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
