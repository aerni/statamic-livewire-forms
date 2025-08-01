<?php

namespace Aerni\LivewireForms\Fields;

use Illuminate\Support\Str;
use Statamic\Fieldtypes\Assets\DimensionsRule;
use Statamic\Fieldtypes\Assets\ImageRule;
use Statamic\Fieldtypes\Assets\MaxRule;
use Statamic\Fieldtypes\Assets\MimesRule;
use Statamic\Fieldtypes\Assets\MimetypesRule;
use Statamic\Fieldtypes\Assets\MinRule;
use Statamic\Fieldtypes\Files;
use Statamic\Forms\Uploaders\AssetsUploader;
use Statamic\Forms\Uploaders\FilesUploader;
use Symfony\Component\Mime\MimeTypes;

class Assets extends Field
{
    protected string $view = 'assets';

    protected function defaultProperty(mixed $default = null): array
    {
        /* We need to initialize the value to an empty array for the upload to work. */
        return [];
    }

    protected function multipleProperty(?bool $multiple = null): bool
    {
        return $multiple ?? $this->field->get('max_files') !== 1;
    }

    protected function fileSizeProperty(): array
    {
        $rules = collect($this->rules)->flatten();

        $minFileSize = $rules->whereInstanceOf(MinRule::class)
            ->flatMap(fn ($rule) => invade($rule)->parameters)
            ->first();

        $maxFileSize = $rules->whereInstanceOf(MaxRule::class)
            ->flatMap(fn ($rule) => invade($rule)->parameters)
            ->first();

        return [
            'min' => $minFileSize,
            'max' => $maxFileSize,
        ];
    }

    protected function fileTypesProperty(): array
    {
        return collect($this->rules)
            ->flatten()
            ->map(fn ($rule) => match (true) {
                $rule instanceof ImageRule => ['image/*'],
                $rule instanceof MimetypesRule => invade($rule)->parameters,
                $rule instanceof MimesRule => collect(invade($rule)->parameters)
                    ->flatMap(fn ($mime) => (new MimeTypes)->getMimeTypes($mime))
                    ->all(),
                default => null,
            })
            ->filter()
            ->first() ?? [];
    }

    protected function dimensionsProperty(): array
    {
        return collect($this->rules)
            ->flatten()
            ->whereInstanceOf(DimensionsRule::class)
            ->flatMap(fn ($rule) => invade($rule)->parameters)
            ->mapWithKeys(fn ($dimension) => [Str::before($dimension, '=') => Str::after($dimension, '=')])
            ->toArray();
    }

    public function process(): mixed
    {
        $this->value = collect($this->value)
            ->when($this->field->fieldtype() instanceof Files,
                fn ($files) => $files->map(fn ($file) => FilesUploader::field($this->field)->upload($file)),
                fn ($files) => $files->map(fn ($file) => AssetsUploader::field($this->field)->upload($file)),
            )
            ->flatten();

        return parent::process();
    }
}
