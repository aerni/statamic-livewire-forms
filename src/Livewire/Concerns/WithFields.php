<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Captcha;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Honeypot;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
            ->map(function ($section, $index) {
                $order = $index + 1;

                return [
                    'handle' => Str::snake($section->display() ?? $order),
                    'id' => "{$this->getId()}-section-{$order}",
                    'display' => __($section->display()),
                    'instructions' => __($section->instructions()),
                    'fields' => $this->fields->intersectByKeys($section->fields()->all()),
                ];
            })
            ->filter(fn ($section) => $section['fields']->isNotEmpty());
    }

    public function section(string $handle): ?array
    {
        return $this->sections()->firstWhere('handle', $handle);
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
