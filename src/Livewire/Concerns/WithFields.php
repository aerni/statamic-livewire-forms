<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Honeypot;
use Aerni\LivewireForms\Form\Fields;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Statamic\Fields\Field;
use Statamic\Fields\Section;

trait WithFields
{
    use WithModels;

    public Collection $fields;

    public function mountWithFields(): void
    {
        $this->fields = $this->fields();

        // $this->mountFields();
    }

    // public function mountFields(): void
    // {
    //     $this->fields->get('first_name')->display('Mounted');
    // }

    // public function hydrateFields(Collection $fields): void
    // {
    //     $fields->get('first_name')->display(rand(1, 100));
    // }

    public function fields(): Collection
    {
        return $this->form->fields()->map(function ($field) {
            $fieldtype = $field->fieldtype()::class;

            $class = $this->models()->get($field->handle())
                ?? $this->models()->get($fieldtype);

            return $class
                ? $class::make($field, $this->getId())
                : throw new \Exception("The field model binding for fieldtype [{$fieldtype}] cannot be found.");
        })->put($this->honeypot->handle, $this->honeypot);
    }

    #[Computed(true)]
    public function honeypot(): Honeypot
    {
        return (Honeypot::make(
            new Field($this->form->honeypot(), []),
            $this->getId()
        ));
    }

    public function sections(): Collection
    {
        return $this->form->blueprint()->tabs()->first()->sections()
            ->map(function ($section, $index) {
                $handle = $section->display() ? Str::snake($section->display()) : $index;

                return [
                    'handle' => $handle,
                    'id' => "{$this->getId()}-section-{$index}-{$handle}",
                    'display' => $section->display(),
                    'instructions' => $section->instructions(),
                    'fields' => $this->sectionFields($section),
                ];
            })
            ->filter(fn ($section) => $section['fields']->isNotEmpty()); // Hide empty sections with no fields.
    }

    public function section(string $handle): ?array
    {
        return $this->sections()->firstWhere('handle', $handle);
    }

    protected function sectionFields(Section $section): Collection
    {
        return $this->fields->intersectByKeys($section->fields()->all()); // Only keep the fields that are part of the section
    }

    #[Renderless]
    #[On('field-conditions-updated')]
    public function submitFieldValue(string $field, bool $passesConditions): void
    {
        $this->fields->get($field)->submittable($passesConditions);
    }
}
