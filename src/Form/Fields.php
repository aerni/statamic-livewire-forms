<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Facades\Models;
use Aerni\LivewireForms\Fields\Captcha;
use Aerni\LivewireForms\Fields\Field;
use Aerni\LivewireForms\Fields\Honeypot;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Fields\Section;
use Statamic\Forms\Form as StatamicForm;

class Fields
{
    protected Collection $models;

    protected Collection $fields;

    protected $hydratedCallbacks = [];

    public function __construct(protected StatamicForm $form, protected string $id)
    {
        $this->models = Models::all();
    }

    public static function make(StatamicForm $form, string $id): self
    {
        return new static($form, $id);
    }

    public function models(array $models): self
    {
        $this->models = collect($models);

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
        return $this
            ->hydrateFields()
            ->removeDuplicateCaptchaFields()
            ->runHydratedCallbacks();
    }

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
            $class = $this->models->get($field->handle())
                ?? $this->models->get($field->fieldtype()::class);

            return $class ? $class::make($field, $this->id) : null;
        })->filter();
    }

    protected function removeDuplicateCaptchaFields(): self
    {
        $duplicates = $this->fields->whereInstanceOf(Captcha::class)->slice(1)->keys();

        $this->fields = $this->fields->except($duplicates);

        return $this;
    }

    public function captcha(): ?Captcha
    {
        return $this->fields->whereInstanceOf(Captcha::class)->first();
    }

    public function honeypot(): Honeypot
    {
        return Honeypot::make(
            new \Statamic\Fields\Field($this->form->honeypot(), []),
            $this->id
        );
    }

    public function defaultValues(): Collection
    {
        /**
         * Only filter null values to preserve empty arrays.
         * This is to ensure that fields like checkboxes are initialized propertly.
         */
        return $this->fields
            ->mapWithKeys(fn ($field, $handle) => [$handle => $field->default])
            ->filter(fn ($value) => ! is_null($value));
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
