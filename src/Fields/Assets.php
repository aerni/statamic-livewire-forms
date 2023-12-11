<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Support\Arr;
use Statamic\Forms\Uploaders\AssetsUploader;
use Aerni\LivewireForms\Fields\Properties\WithMultiple;

class Assets extends Field
{
    use WithMultiple;

    protected string $view = 'assets';

    public function process(): mixed
    {
        $value = parent::process();

        return collect(Arr::wrap($value))
            ->flatMap(fn ($file) => AssetsUploader::field($this->handle)->upload($file))
            ->all();
    }

    protected function multipleProperty(): bool
    {
        return $this->field->get('max_files') !== 1;
    }

    protected function defaultProperty(): ?array
    {
        return $this->multipleProperty() ? [] : null;
    }

    protected function rulesProperty(): array
    {
        $rules = parent::rulesProperty();

        if ($this->multipleProperty()) {
            return $rules;
        }

        return collect($rules)
            ->filter(fn ($rule) => ! in_array($rule, ['array', 'max:1']))
            ->all();
    }
}
