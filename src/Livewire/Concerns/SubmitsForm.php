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

        try {
            $this->updateSubmittableFields($submittableFields)
                ->handleSpam()
                ->handleSubmission()
                ->handleSuccess();
        } catch (SilentFormFailureException) {
            $this->handleSuccess();
        }
    }

    protected function updateSubmittableFields(array $submittableFields): self
    {
        collect($submittableFields)->each(fn ($submittable, $field) => $this->fields->get($field)->submittable($submittable));

        return $this;
    }
}
