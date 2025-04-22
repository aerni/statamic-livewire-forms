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
        /**
         * When handling array fields like checkboxes, the $key can look like "services.value.0".
         * This can cause issues with validation, since it targets a specific item instead of the whole array.
         * The following code fixes this by pointing the $key to the full array "services.value" instead.
         */
        $key = str($key)->explode('.')->slice(0, 2)->prepend('fields')->join('.');

        $this->validateOnly($key);

        /**
         * Explicitly forget the errors of this field after validation has passed
         * so that we don't restore them in some edge case scenarios.
         */
        if ($this->isWizardForm()) {
            $this->resetStepErrorBag($key);
        }
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
