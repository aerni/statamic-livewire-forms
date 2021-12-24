<?php

namespace Aerni\LivewireForms\Form;

use Statamic\Forms\Form;
use Statamic\Fields\Field;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Fields
{
    protected Form $form;
    protected string $id;
    protected Collection $fields;

    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->id = Str::random(9);
    }

    public static function make(Form $form): Collection
    {
        return (new static($form))->process()->fields;
    }

    protected function process(): self
    {
        return $this
            ->preProcess()
            ->removeDuplicateCaptchaFields()
            ->addHoneypotField();
    }

    protected function preProcess(): self
    {
        $this->fields = $this->form->fields()->map(function ($field) {
            return [
                'label' => __($field->get('display')),
                'instructions' => __($field->get('instructions')),
                'handle' => "{$this->id}_{$field->handle()}",
                'key' => 'data.' . $field->handle(),
                'type' => $this->assignFieldType($field->get('type')),
                'input_type' => $this->assignFieldInputType($field->get('type'), $field->get('input_type')),
                'options' => $this->getTranslatedFieldOptions($field),
                'inline' => $field->get('inline'),
                'default' => $field->get('default'),
                'placeholder' => __($field->get('placeholder')),
                'autocomplete' => $field->get('autocomplete') ?? 'off',
                'width' => $field->get('width') ?? 100,
                'rules' => collect($field->rules())->flatten()->toArray(),
                'realtime' => $field->get('realtime'),
                'show_label' => $field->get('show_label') ?? true,
                'cast_booleans' => $field->get('cast_booleans') ?? false,
                'show' => $this->shouldShowField($field),
            ];
        });

        return $this;
    }

    protected function removeDuplicateCaptchaFields(): self
    {
        $duplicates = $this->fields->filter(function ($field) {
            return $field['type'] === 'captcha';
        })->slice(1);

        $this->fields = $this->fields->reject(function ($field) use ($duplicates) {
            return $duplicates->contains($field);
        });

        return $this;
    }

    protected function addHoneypotField(): self
    {
        $handle = $this->form->honeypot();

        $this->fields = $this->fields->merge([
            $handle => [
                'label' => Str::ucfirst($handle),
                'handle' => "{$this->id}_{$handle}",
                'key' => 'data.' . $handle,
                'type' => 'honeypot',
                'width' => 100,
                'rules' => [],
                'show' => false,
            ]
        ]);

        return $this;
    }

    protected function getTranslatedFieldOptions($field): array
    {
        return collect($field->get('options'))->map(function ($option) {
            return __($option);
        })->toArray();
    }

    protected function assignFieldType(string $type): string
    {
        $types = [
            'assets' => 'file',
            'captcha' => 'captcha',
            'checkboxes' => 'checkboxes',
            'integer' => 'input',
            'radio' => 'radios',
            'select' => 'select',
            'text' => 'input',
            'textarea' => 'textarea',
        ];

        return $types[$type] ?? 'input';
    }

    protected function assignFieldInputType(string $fieldType, ?string $intputType): ?string
    {
        $types = [
            'assets' => 'file',
            'checkboxes' => 'checkbox',
            'integer' => 'number',
            'radio' => 'radio',
        ];

        return $types[$fieldType] ?? $intputType;
    }

    protected function shouldShowField(Field $field): bool
    {
        [$type, $conditions] = $this->getConditions($field);

        if ($conditions->isEmpty()) {
            return true;
        }

        $conditions = $conditions->map(function ($condition, $field) {
            [$operator, $expectedValue] = explode(' ', $condition);

            $actualValue = collect($this->data)->get($field);

            return $this->evaluateCondition($actualValue, $operator, $expectedValue);
        });

        return $this->evaluateConditions($type, $conditions);
    }

    protected function getConditions(Field $field): array
    {
        $conditions = array_filter([
            'if' => $field->get('if'),
            'if_any' => $field->get('if_any'),
            'unless' => $field->get('unless'),
            'unless_any' => $field->get('unless_any'),
        ]);

        return [
            array_key_first($conditions),
            collect(array_first($conditions)),
        ];
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

    protected function evaluateCondition(string|array|null $actualValue, string $operator, string $expectedValue): bool
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
