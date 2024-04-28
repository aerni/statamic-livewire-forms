<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Locked;
use Illuminate\Support\MessageBag;

trait HandlesValidation
{
    #[Locked]
    public array $allStepErrors = [];

    public function bootHandlesValidation(): void
    {
        /**
         * Remove all fields that are not submittable from the data before validation to replicate
         * Statamic's suggested validation pattern: https://statamic.dev/conditional-fields#validation
         * This allows us to conditionally apply validation to conditionally shown fields using the 'sometimes' rule.
         */
        $this->withValidator(function ($validator) {
            collect($validator->getValue('fields'))
                ->filter(fn ($value, $field) => $this->submittableFields[$field])
                ->pipe(fn ($fields) => $validator->setValue('fields', $fields));
        });
    }

    public function storeAllStepErrors(): void
    {
        $currentErrors = $this->getErrorBag()->messages();

        $stepFields = $this->currentStep()->fields()->map->key()->flip();

        $resolvedErrors = collect($stepFields)->diffKeys($currentErrors);

        $this->allStepErrors = collect($this->allStepErrors)
            ->merge($currentErrors)
            ->diffKeys($resolvedErrors)
            ->toArray();
    }

    public function restoreAllStepErrors(): void
    {
        $this->setErrorBag(new MessageBag($this->allStepErrors));
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
