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
    protected Collection $fields;
    protected $hydratedCallbacks = [];

    public function __construct(protected StatamicForm $form, protected string $id, protected Collection $data)
    {
        $this->models = Models::all();
    }

    public static function make(StatamicForm $form, string $id, Collection $data): self
    {
        return new static($form, $id, $data);
    }

    public function models(array $models): self
    {
        $this->models = collect($models);

        return $this;
    }

    public function data(Collection $data): self
    {
        $this->data = $data;

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
        return $this->fields->first(function ($field) use ($key) {
            return $field->key === $key;
        });
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
            ->processConditions()
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
        $this->fields = $this->form->fields()->map(function ($field) {
            $class = $this->models->get($field->handle()) ?? $this->models->get(get_class($field->fieldtype()));

            return $class ? $class::make($field, $this->id) : null;
        })->filter();

        return $this;
    }

    protected function captcha(): Collection
    {
        return $this->fields->whereInstanceOf(Captcha::class);
    }

    protected function removeDuplicateCaptchaFields(): self
    {
        $duplicates = $this->captcha()->slice(1)->keys();

        $this->fields = $this->fields->except($duplicates);

        return $this;
    }

    public function processConditions(): self
    {
        $this->fields = $this->fields->each(fn ($field) => $field->show(Conditions::process($field, $this->data)));

        return $this;
    }

    public function defaultValues(): Collection
    {
        /**
         * When submitting a form, we need to preserve the captcha response on the Livewire component
         * until the captcha expires itself. Otherwise we will get a `The Captcha field is required.`
         * error (when submitting the form again without reloading the page) because the response value is missing.
         */
        $captcha = $this->captcha()->keys()->first();

        return $this->fields->mapWithKeys(function ($field, $handle) {
            return [$handle => $field->default];
        })->except($captcha);
    }

    public function validationRules(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field->key => $field->rules];
        })->toArray();
    }

    public function validationAttributes(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field->key => $field->label];
        })->toArray();
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
