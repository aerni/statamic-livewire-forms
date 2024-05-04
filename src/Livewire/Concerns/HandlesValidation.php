<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Locked;

trait HandlesValidation
{
    // TODO: Should this only be added for the WizardForm?
    #[Locked]
    public array $stepErrors = [];

    public function bootHandlesValidation(): void
    {
        $this->withValidator(function ($validator) {
            /**
             * Remove all fields that are not submittable from the data before validation to replicate
             * Statamic's suggested validation pattern: https://statamic.dev/conditional-fields#validation
             * This allows us to conditionally apply validation to conditionally shown fields using the 'sometimes' rule.
             */
            collect($validator->getValue('fields'))
                ->filter(fn ($value, $field) => $this->submittableFields[$field])
                ->pipe(fn ($fields) => $validator->setValue('fields', $fields));

            /**
             * Validation errors in a WizardForm need special treatment.
             */
            if (property_exists($this, 'currentStep')) {
                $validator->after(function ($validator) {
                    /* Store the current errors so that we can restore them after successfull validation. */
                    $this->stepErrors = array_merge($this->stepErrors, $validator->errors()->messages());

                    /**
                     * If the validation of the current step fails, we need to merge all previously stored errors
                     * to ensure that we don't reset the validation state of other steps in the process.
                     */
                    if ($validator->errors()->hasAny($this->currentStep()->fields()->map->key()->all())) {
                        /* Ensure we don't add errors that already exist. */
                        $errors = array_diff_key($this->stepErrors, $validator->errors()->messages());
                        $validator->errors()->merge($errors);
                    }
                });
            }
        });
    }

    public function resetStepErrorBag(string|array $keys): void
    {
        array_forget($this->stepErrors, $keys);

        $this->setErrorBag($this->stepErrors);
    }

    protected function rules(): array
    {
        return $this->fields
            ->mapWithKeys(fn ($field) => $field->rules())
            ->toArray();
    }

    protected function validationAttributes(): array
    {
        return $this->fields
            ->mapWithKeys(fn ($field) => $field->validationAttributes())
            ->toArray();
    }
}
