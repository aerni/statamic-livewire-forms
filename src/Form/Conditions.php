<?php

namespace Aerni\LivewireForms\Form;

use Aerni\LivewireForms\Fields\Field;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Conditions
{
    public function process(Field $field, Collection $data): bool
    {
        /**
         * The field's show value should take precedence over its conditions.
         * Always hide a field if `show` is set to `false`.
         */
        if (! $field->show) {
            return false;
        }

        // Always show the field if there are no conditions.
        if (! $field->conditions) {
            return true;
        }

        $type = array_key_first($field->conditions);
        $conditions = array_first($field->conditions);

        $conditions = collect($conditions)->map(function ($condition, $field) use ($data) {
            [$operator, $expectedValue] = explode(' ', $condition);

            $actualValue = $data->get($field);

            return $this->evaluateCondition($actualValue, $operator, $expectedValue);
        });

        return $this->evaluateConditions($type, $conditions);
    }

    protected function evaluateConditions(string $conditionsType, Collection $conditions): bool
    {
        return match ($conditionsType) {
            'if' => $conditions->count() === $conditions->filter()->count(),
            'if_any' => $conditions->filter()->isNotEmpty(),
            'unless' => $conditions->filter()->isEmpty(),
            'unless_any' => $conditions->count() !== $conditions->filter()->count(),
            default => false,
        };
    }

    protected function evaluateCondition(string|bool|array|null $actualValue, string $operator, string $expectedValue): bool
    {
        return match ($operator) {
            'equals' => $actualValue == $expectedValue,
            'not' => $actualValue != $expectedValue,
            'contains' => is_array($actualValue)
                ? ! array_diff(explode(',', $expectedValue), $actualValue)
                : Str::contains($actualValue, $expectedValue),
            'contains_any' => is_array($actualValue)
                ? (bool) array_intersect(explode(',', $expectedValue), $actualValue)
                : Str::contains($actualValue, explode(',', $expectedValue)),
            '===' => $actualValue === $expectedValue,
            '!==' => $actualValue !== $expectedValue,
            '>' => $actualValue > $expectedValue,
            '>=' => $actualValue >= $expectedValue,
            '<' => $actualValue < $expectedValue,
            '<=' => $actualValue <= $expectedValue,
            default => false,
        };
    }
}
