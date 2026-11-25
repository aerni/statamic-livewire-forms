<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Support\Collection;
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

        return $this->fields()
            ->mapWithKeys(fn (Field $field) => $field->rules)
            ->all();
    }

    public function validationAttributes(): array
    {
        return [
            $this->key => $this->display,
            ...$this->fields()->mapWithKeys(fn (Field $field) => [$field->key => $field->display]),
        ];
    }
}
