<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Validation\ValidationException;
use Statamic\Exceptions\FormRestrictedException;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Forms\SubmitForm;

trait SubmitsForm
{
    use HandlesSubmission;
    use HandlesSuccess;
    use HandlesValidation;

    public function submit(): void
    {
        $this->validate();

        $action = app(SubmitForm::class)->form($this->form);

        $this->handleFormEvents($action);

        try {
            $this->submission = $action->submit(
                data: $this->submittableData(),
                files: $this->submittableFiles(),
            )->submission;
        } catch (ValidationException $exception) {
            throw $this->withLivewireFieldKeys($exception);
        } catch (SilentFormFailureException) {
            $this->submission = $action->submission();
        } catch (FormRestrictedException $exception) {
            throw ValidationException::withMessages(['*' => $exception->getMessage()]);
        }

        $this->handleSuccess();
    }
}
