<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Exceptions\FormHasNoFieldsException;
use Aerni\LivewireForms\Fields\Captcha;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Honeypot;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Statamic\Fields\Field as StatamicField;

trait WithFields
{
    use HandlesValues;
    use WithModels;

    public Collection $fields;

    public function mountWithFields(): void
    {
        $this->fields = $this->fields();

        $this->mountedFields($this->fields);
    }

    public function mountedFields(Collection $fields): void
    {
        //
    }

    public function updatedFields(mixed $value, string $key): void
    {
        $key = $this->realtimeValidationKey($key);

        $this->validateOnly($key);

        /**
         * Explicitly forget the errors of this field after validation has passed
         * so that we don't restore them in some edge case scenarios.
         */
        if ($this->isWizardForm()) {
            $this->resetStepErrorBag($key);
        }
    }

    /**
     * Map a Livewire fields.* update path to the validateOnly target.
     *
     * Trailing numeric indexes are checkbox/array item paths and must validate
     * the parent array (e.g. "services.value.0" → "fields.services.value",
     * "items.value.0.options.1" → "fields.items.value.0.options").
     * Nested property paths keep their concrete key
     * (e.g. "items.value.0.amount" → "fields.items.value.0.amount").
     */
    protected function realtimeValidationKey(string $key): string
    {
        $parts = str($key)->explode('.');

        if (is_numeric($parts->last())) {
            $parts = $parts->slice(0, -1);
        }

        return $parts->prepend('fields')->join('.');
    }

    protected function fields(): Collection
    {
        $honeypot = Honeypot::make(new StatamicField($this->form->honeypot(), []));

        throw_if($this->form->fields()->isEmpty(), new FormHasNoFieldsException($this->handle));

        return $this->form->fields()
            ->map(fn ($field) => $this->makeFieldFromModel($field))
            ->put($honeypot->handle, $honeypot);
    }

    protected function makeFieldFromModel(StatamicField $field): Field
    {
        $fieldtype = $field->fieldtype()::class;

        $class = $this->models()->get($field->handle())
            ?? $this->models()->get($fieldtype);

        return $class
            ? $class::make($field)
            : throw new \Exception("The field model binding for fieldtype [{$fieldtype}] cannot be found.");
    }

    #[Computed]
    public function honeypot(): Honeypot
    {
        return $this->fields->whereInstanceOf(Honeypot::class)->first();
    }

    protected function captcha(): ?Captcha
    {
        return $this->fields->whereInstanceOf(Captcha::class)->first();
    }
}
