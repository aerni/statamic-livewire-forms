<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Statamic\Exceptions\SilentFormFailureException;

trait SubmitsForm
{
    use HandlesSpam;
    use HandlesSubmission;
    use HandlesSuccess;
    use HandlesValidation;

    public function submit(array $submittableFields): void
    {
        $this->validate();

        $this->submittableFields = $submittableFields;

        try {
            $this->handleSpam()->handleSubmission()->handleSuccess();
        } catch (SilentFormFailureException) {
            $this->handleSuccess();
        }
    }
}
