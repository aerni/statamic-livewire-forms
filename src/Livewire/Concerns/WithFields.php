<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Captcha;
use Aerni\LivewireForms\Fields\Honeypot;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Statamic\Fields\Field;

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

    protected function fields(): Collection
    {
        $fields = $this->form->fields()->map(function ($field) {
            $fieldtype = $field->fieldtype()::class;

            $class = $this->models()->get($field->handle())
                ?? $this->models()->get($fieldtype);

            return $class
                ? $class::make(field: $field, id: $this->getId())
                : throw new \Exception("The field model binding for fieldtype [{$fieldtype}] cannot be found.");
        });

        $honeypot = Honeypot::make(
            field: new Field($this->form->honeypot(), []),
            id: $this->getId()
        );

        return $fields->put($honeypot->handle, $honeypot);
    }

    #[Computed]
    public function sections(): Collection
    {
        return $this->form->blueprint()->tabs()->first()->sections()
            ->map(function ($section, $index) {
                $handle = $section->display() ? Str::snake($section->display()) : $index;

                return [
                    'handle' => $handle,
                    'id' => "{$this->getId()}-section-{$index}-{$handle}",
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
