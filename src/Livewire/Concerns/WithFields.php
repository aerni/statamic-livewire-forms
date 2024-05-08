<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

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
        $this->validateOnly("fields.{$key}");

        /**
         * Explicitly forget the errors of this field after validation has passed
         * so that we don't restore them in some edge case scenarios.
         */
        if ($this->isWizardForm()) {
            $this->resetStepErrorBag("fields.{$key}");
        }
    }

    protected function fields(): Collection
    {
        $honeypot = Honeypot::make(new StatamicField($this->form->honeypot(), []));

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

    public function honeypot(): Honeypot
    {
        return $this->fields->whereInstanceOf(Honeypot::class)->first();
    }

    protected function captcha(): ?Captcha
    {
        return $this->fields->whereInstanceOf(Captcha::class)->first();
    }
}
