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

        $this->updateSubmittableFields($submittableFields);

        try {
            $this->handleSpam()->handleSubmission()->handleSuccess();
        } catch (SilentFormFailureException) {
            $this->handleSuccess();
        }
    }

    protected function updateSubmittableFields(array $submittableFields): void
    {
        collect($submittableFields)->each(fn ($value, $key) => $this->fields->get($key)->submittable($value));
    }
}
