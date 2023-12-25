<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Support\Arr;
use Statamic\Forms\Uploaders\AssetsUploader;

class Assets extends Field
{
    protected string $view = 'assets';

    protected function multipleProperty(): bool
    {
        return $this->field->get('max_files') !== 1;
    }

    protected function defaultProperty(): ?array
    {
        return $this->multiple ? [] : null;
    }

    protected function rulesProperty(): array
    {
        $rules = parent::rulesProperty();

        if ($this->multiple) {
            return $rules;
        }

        return collect($rules)
            ->filter(fn ($rule) => ! in_array($rule, ['array', 'max:1']))
            ->all();
    }

    public function process(): mixed
    {
        $this->value = collect(Arr::wrap($this->value))
            ->flatMap(fn ($file) => AssetsUploader::field($this->handle)->upload($file));

        return parent::process();
    }
}
