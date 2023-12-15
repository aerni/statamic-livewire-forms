<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Concerns\HandlesProperties;
use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithHidden;
use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithInstructionsPosition;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithView;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithWireModel;
use Illuminate\Contracts\Support\Arrayable;
use Statamic\Fields\Field as StatamicField;
use Statamic\Support\Str;
use Statamic\Support\Traits\FluentlyGetsAndSets;

abstract class Field implements Arrayable
{
    use FluentlyGetsAndSets;
    use HandlesProperties;
    use WithConditions;
    use WithDefault;
    use WithHandle;
    use WithHidden;
    use WithId;
    use WithInstructions;
    use WithInstructionsPosition;
    use WithKey;
    use WithLabel;
    use WithRules;
    use WithShowLabel;
    use WithView;
    use WithWidth;
    use WithWireModel;

    protected mixed $value = null;
    protected bool $submittable = true;

    public function __construct(protected StatamicField $field, protected string $id)
    {
        //
    }

    public static function make(StatamicField $field, string $id): self
    {
        return new static($field, $id);
    }

    public function rules(): array
    {
        return [$this->key => $this->rules];
    }

    public function validationAttributes(): array
    {
        return [$this->key => $this->label];
    }

    public function process(): mixed
    {
        if (! $this->submittable()) {
            return null;
        }

        if ($this->cast_booleans && in_array($this->value, ['true', 'false'])) {
            return Str::toBool($this->value);
        }

        return $this->value;
    }

    public function value(mixed $value = null): mixed
    {
        return $this->fluentlyGetOrSet('value')
            ->getter(fn ($value) => is_null($value) ? $this->default : $value)
            ->args(func_get_args());
    }

    public function submittable(?bool $submittable = null): bool|self
    {
        return $this->fluentlyGetOrSet('submittable')
            ->getter(fn ($submittable) => $this->always_save ? true : $submittable)
            ->args(func_get_args());
    }

    public function toArray(): array
    {
        return [
            'handle' => $this->field->handle(),
            'config' => $this->field->config(),
            'properties' => $this->properties(),
            'value' => $this->value(),
            'submittable' => $this->submittable(),
        ];
    }
}
