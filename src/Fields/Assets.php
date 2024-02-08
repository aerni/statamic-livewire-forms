<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Support\Arr;
use Statamic\Forms\Uploaders\AssetsUploader;

class Assets extends Field
{
    protected string $view = 'assets';

    protected function multipleProperty(?bool $multiple = null): bool
    {
        return $multiple ?? $this->field->get('max_files') !== 1;
    }

    protected function defaultProperty(mixed $default = null): ?array
    {
        return $this->multiple ? [] : null;
    }

    protected function rulesProperty(string|array|null $rules = null): array
    {
        $rules = parent::rulesProperty($rules);

        if ($this->multiple) {
            return $rules;
        }

        /* Remove validation rules that would only work if multiple is enabled */
        $rules = collect(array_first($rules))
            ->filter(fn ($rule) => $rule !== 'array')
            ->filter(fn ($rule) => $rule !== "min:{$this->min_files}")
            ->filter(fn ($rule) => $rule !== "max:{$this->max_files}")
            ->values()->all();

        return [$this->key => $rules];
    }

    public function process(): mixed
    {
        $this->value = collect(Arr::wrap($this->value))
            ->flatMap(fn ($file) => AssetsUploader::field($this->handle)->upload($file));

        return parent::process();
    }
}
