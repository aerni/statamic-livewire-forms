<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Statamic\Exceptions\SilentFormFailureException;

trait SubmitsForm
{
    use HandlesSpam;
    use HandlesSubmission;
    use HandlesSuccess;
    use HandlesValidation;

    public function submit(): void
    {
        dd($this->synthFields->values());
        $this->validate();

        try {
            $this->handleSpam()->handleSubmission()->handleSuccess();
        } catch (SilentFormFailureException) {
            $this->handleSuccess();
        }
    }
}
