<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Statamic\Fields\Field as StatamicField;

class Group extends Field
{
    protected string $view = 'group';

    public function fields(): Collection
    {
        return $this->field->fieldtype()->fields()->all()
            ->map(fn (StatamicField $field) => $this->makeNestedField($field));
    }

    protected function makeNestedField(StatamicField $field): Field
    {
        $model = invade(Livewire::current())->makeFieldFromModel($field);

        return $model->properties([
            ...$model->properties(),
            'key' => "{$this->key}.{$field->handle()}",
            'id' => "{$this->id}-{$field->handle()}",
        ]);
    }

    protected function defaultProperty(mixed $default = null): array
    {
        return $default ?? $this->fields()->map(fn (Field $field) => $field->default)->toArray();
    }

    protected function rulesProperty(string|array|null $rules = null): array
    {
        if (! is_null($rules)) {
            return parent::rulesProperty($rules);
        }

        /* The group's rules also contain the rules of its nested fields, e.g. "group.name". */
        return collect($this->field->rules())
            ->mapWithKeys(fn ($rules, $handle) => [
                $this->key.Str::after($handle, $this->handle) => $rules,
            ])
            ->toArray();
    }

    public function validationAttributes(): array
    {
        return [
            $this->key => $this->display,
            ...$this->fields()->mapWithKeys(fn (Field $field) => [$field->key => $field->display]),
        ];
    }
}
