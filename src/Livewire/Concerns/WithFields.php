<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Form\Section;
use Aerni\LivewireForms\Fields\Captcha;
use Aerni\LivewireForms\Fields\Honeypot;
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

    public function updatedFields($value, $key): void
    {
        $this->validateOnly("fields.{$key}");
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

    #[Computed]
    public function sections(): Collection
    {
        return $this->form->blueprint()->tabs()->first()->sections()
            ->filter(fn ($section) => $section->fields()->all()->isNotEmpty())
            ->values()
            ->map(fn ($section, $index) => (new Section(
                fields: $this->fields->intersectByKeys($section->fields()->all()),
                order: $index + 1,
                display: $section->display(),
                instructions: $section->instructions(),
            )));
    }

    public function section(string $handle): ?Section
    {
        return $this->sections->firstWhere(fn ($section) => $section->handle() === $handle);
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
