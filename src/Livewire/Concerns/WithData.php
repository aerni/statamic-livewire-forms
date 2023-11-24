<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Statamic\Support\Str;

trait WithData
{
    public array $data = [];

    public function mountWithData(): void
    {
        $this->data = $this->defaultData;
    }

    // The persisted computed property ensures that we can reset the data to its mount state.
    #[Computed(true)]
    protected function defaultData(): array
    {
        return $this->fields->defaultValues()
            ->merge($this->data)
            ->all();
    }

    protected function captchaValue(): array
    {
        return collect($this->data)
            ->only($this->fields->captcha()?->handle ?? [])
            ->all();
    }

    protected function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    protected function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    protected function normalizedDataForSubmission(): array
    {
        return collect($this->data)->map(function ($value, $key) {
            $field = $this->fields->get($key);

            // Return early if a field can't be found, else we'll run into errors with the below code.
            if (is_null($field)) {
                return null;
            }

            // Only keep values of fields that should be submitted, e.g. if 'always_save' is on.
            if (! $this->fieldsToSubmit->get($field->handle)) {
                return null;
            }

            // Don't save the captcha response.
            if ($field->field()->type() === 'captcha') {
                return null;
            }

            // Cast to booleans if enabled in the config.
            if ($field->cast_booleans && in_array($value, ['true', 'false'])) {
                return Str::toBool($value);
            }

            // Cast to integers if the input type is 'number'.
            if ($field->input_type === 'number') {
                return (int) $value;
            }

            // Otherwise, just return the value.
            return $value;
        })->all();
    }

    protected function temporaryUploadedFiles(): array
    {
        return collect($this->data)
            ->whereInstanceOf(TemporaryUploadedFile::class)
            ->all();
    }
}
