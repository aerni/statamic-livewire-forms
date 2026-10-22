<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Validation\ValidationException;
use Statamic\Exceptions\FormRestrictedException;
use Statamic\Exceptions\SilentFormFailureException;

trait SubmitsForm
{
    use HandlesSpam;
    use HandlesSubmission;
    use HandlesSuccess;
    use HandlesValidation;

    public function submit(): void
    {
        $this->validate();

        try {
            $this->handleSubmission()->handleSuccess();
        } catch (SilentFormFailureException) {
            $this->handleSuccess();
        } catch (FormRestrictedException $exception) {
            throw ValidationException::withMessages(['*' => $exception->getMessage()]);
        }
    }
}
