<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Honeypot;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Fields\Section;
use Statamic\Forms\Form;

class Fields
{
    protected Collection $fields;

    protected Collection $models;

    protected $hydratedCallbacks = [];

    public function __construct(protected Form $form, protected string $id)
    {
        //
    }

    public static function make(Form $form, string $id): self
    {
        return new static($form, $id);
    }

    public function models(?array $models = null): Collection|self
    {
        $defaultModels = collect(config('livewire-forms.models'));

        if (is_null($models)) {
            return $this->models ?? $defaultModels;
        }

        $this->models = $defaultModels->merge($models);

        return $this;
    }

    public function values(?array $values = null): array|self
    {
        if (is_null($values)) {
            return $this->fields->map(fn ($field) => $field->value())->all();
        }

        collect($values)->each(fn ($value, $field) => $this->get($field)->value($value));

        return $this;
    }

    public function all(): Collection
    {
        return $this->fields;
    }

    public function get(string $field): ?Field
    {
        return $this->fields->get($field);
    }

    public function getByKey(string $key): ?Field
    {
        return $this->fields->first(fn ($field) => $field->key === $key);
    }

    public function getByType(string $key): Collection
    {
        return $this->fields->filter(fn ($field) => $field->field()->type() === $key);
    }

    protected function getSectionFields(Section $section): Collection
    {
        return $this->fields->intersectByKeys($section->fields()->all()); // Only keep the fields that are part of the section
    }

    public function sections(): Collection
    {
        return $this->form->blueprint()->tabs()->first()->sections()
            ->map(function ($section, $index) {
                $handle = $section->display() ? Str::snake($section->display()) : $index;

                return [
                    'handle' => $handle,
                    'id' => "{$this->id}-section-{$index}-{$handle}",
                    'display' => $section->display(),
                    'instructions' => $section->instructions(),
                    'fields' => $this->getSectionFields($section),
                ];
            })
            ->filter(fn ($section) => $section['fields']->isNotEmpty()); // Hide empty sections with no fields.
    }

    public function section(string $handle): ?array
    {
        return $this->sections()->firstWhere('handle', $handle);
    }

    public function hydrate(): self
    {
        return $this->hydrateFields()->runHydratedCallbacks();
    }

    // TODO: Can we get rid of this, now that we have a fieldsSynth?
    protected function runHydratedCallbacks(): self
    {
        foreach ($this->hydratedCallbacks as $callback) {
            $callback($this);
        }

        return $this;
    }

    public function hydrated(\Closure $callback): self
    {
        $this->hydratedCallbacks[] = $callback;

        return $this;
    }

    protected function hydrateFields(): self
    {
        $this->fields = $this->makeFields($this->form->fields());

        return $this;
    }

    protected function makeFields(Collection $fields): Collection
    {
        return $fields->map(function ($field) {
            $fieldtype = $field->fieldtype()::class;

            $class = $this->models()->get($field->handle()) ?? $this->models()->get($fieldtype);

            return $class
                ? $class::make($field, $this->id)
                : throw new \Exception("The field model binding for fieldtype [{$fieldtype}] cannot be found.");
        });
    }

    public function honeypot(): Honeypot
    {
        return Honeypot::make(
            new \Statamic\Fields\Field($this->form->honeypot(), []),
            $this->id
        );
    }

    // TODO: Probably don't need this anymore as each field is setting its default value.
    public function defaultValues(): Collection
    {
        return $this->fields->mapWithKeys(fn ($field, $handle) => [$handle => $field->default]);
    }

    public function validationRules(): array
    {
        return $this->fields
            ->mapWithKeys(fn ($field) => $field->rules())
            ->toArray();
    }

    public function validationAttributes(): array
    {
        return $this->fields
            ->mapWithKeys(fn ($field) => $field->validationAttributes())
            ->toArray();
    }
}
