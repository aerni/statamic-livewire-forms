<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Honeypot;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Statamic\Fields\Field;

trait WithFields
{
    use WithModels;

    public Collection $fields;

    public function mountWithFields(): void
    {
        $this->fields = $this->fields();

        $this->mountedFields($this->fields);
    }

    public function mountedFields($fields)
    {
        //
    }

    // TODO: This doesn't work in conjunnction with the update fields event.
    // public function hydrateFields(Collection $fields): void
    // {
    //     $fields->get('toggle')->display(rand(1, 100));
    // }

    public function fields(): Collection
    {
        return $this->form->fields()->map(function ($field) {
            $fieldtype = $field->fieldtype()::class;

            $class = $this->models()->get($field->handle())
                ?? $this->models()->get($fieldtype);

            return $class
                ? $class::make(field: $field, id: $this->getId())
                : throw new \Exception("The field model binding for fieldtype [{$fieldtype}] cannot be found.");
        })->put($this->honeypot->handle, $this->honeypot);
    }

    #[Computed(true)]
    public function honeypot(): Honeypot
    {
        return Honeypot::make(
            field: new Field($this->form->honeypot(), []),
            id: $this->getId()
        );
    }

    protected function captcha(): ?Captcha
    {
        return $this->fields->whereInstanceOf(Captcha::class)->first();
    }

    public function sections(): Collection
    {
        return $this->form->blueprint()->tabs()->first()->sections()
            ->map(function ($section, $index) {
                $handle = $section->display() ? Str::snake($section->display()) : $index;

                return [
                    'handle' => $handle,
                    'id' => "{$this->getId()}-section-{$index}-{$handle}",
                    'display' => $section->display(), // TODO: Make translatable.
                    'instructions' => $section->instructions(), // TODO: Make translatable
                    'fields' => $this->fields->intersectByKeys($section->fields()->all()),
                ];
            })
            ->filter(fn ($section) => $section['fields']->isNotEmpty()); // Remove empty sections with no fields.
    }

    public function section(string $handle): ?array
    {
        return $this->sections()->firstWhere('handle', $handle);
    }

    #[Renderless]
    #[On('field-conditions-updated')]
    public function updateFieldSubmittable(string $field, bool $passesConditions): void
    {
        $this->fields->get($field)->submittable($passesConditions);
    }
}
