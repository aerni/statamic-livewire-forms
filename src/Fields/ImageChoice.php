<?php

namespace Aerni\LivewireForms\Fields;

use Aerni\LivewireForms\Fields\Properties\WithMultiple;
use Illuminate\Support\Arr;

class ImageChoice extends Field
{
    use WithMultiple;

    protected string $view = 'image_choice';

    protected function optionsProperty(?array $options = null): array
    {
        return $options ?? Arr::get($this->field->fieldtype()->extraRenderableFieldData(), 'options', []);
    }

    protected function defaultProperty(mixed $default = null): string|array|null
    {
        $default = collect($this->options)
            ->pluck('key')
            ->intersect(Arr::wrap($default ?? $this->field->defaultValue()))
            ->values();

        return $this->multiple ? $default->toArray() : $default->first();
    }
}
