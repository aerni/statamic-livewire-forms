<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Facades\Conditions;
use Aerni\LivewireForms\Facades\Models;
use Aerni\LivewireForms\Fields\Captcha;
use Aerni\LivewireForms\Fields\Field;
use Illuminate\Support\Collection;
use Statamic\Fields\Validator;
use Statamic\Forms\Form as StatamicForm;

class Fields
{
    protected Collection $models;
    protected Collection $data;
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

    public function data(array $data): self
    {
        $this->data = collect($data);

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

    public function groups(): Collection
    {
        return $this->fields->groupBy(fn ($field) => $field->group);
    }

    public function group(string $group): Collection
    {
        return $this->groups()->only($group);
    }

    public function hydrate(): self
    {
        return $this
            ->hydrateFields()
            ->removeDuplicateCaptchaFields()
            ->runHydratedCallbacks()
            ->processConditions();
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
        $this->fields = $this->form->fields()->map(function ($field) {
            $class = $this->models->get($field->handle())
                ?? $this->models->get($field->fieldtype()::class);

            return $class ? $class::make($field, $this->id) : null;
        })->filter();

        return $this;
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

    protected function processConditions(): self
    {
        $data = $this->data->isNotEmpty()
            ? $this->data
            : $this->defaultValues()->filter();

        $this->fields = $this->fields->each(fn ($field) => $field->show(Conditions::process($field, $data)));

        return $this;
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
            ->mapWithKeys(fn ($field) => [$field->key => $field->rules])
            ->toArray();
    }

    public function validationAttributes(): array
    {
        return $this->fields
            ->mapWithKeys(fn ($field) => [$field->key => $field->label])
            ->toArray();
    }

    public function realtimeValidationRules(string $field): array
    {
        $field = $this->getByKey($field) ?? $field;

        // Don't use realtime validation if the field can't be found (e.g. honeypot).
        if (is_string($field)) {
            return [$field => []];
        }

        $realtime = $field->realtime
            ?? $this->form->blueprint()->contents()['sections']['main']['realtime']
            ?? config('livewire-forms.realtime', true);

        // Use the regular validation rules if "realtime: true".
        if ($realtime === true) {
            return [$field->key => $field->rules];
        }

        // Make sure to always get an array of realtime rules.
        $realtime = Validator::explodeRules($realtime);

        // Remove any realtime rules that are not part of the regular validation rules.
        $realtime = array_intersect($realtime, $field->rules);

        return [$field->key => $realtime];
    }
}
