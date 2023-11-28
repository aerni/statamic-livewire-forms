<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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

    protected function data(): Collection
    {
        return collect($this->data);
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

    protected function captchaValue(): array
    {
        return $this->data()
            ->only($this->fields->getByType('captcha')->first()?->handle ?? [])
            ->all();
    }

    protected function normalizedDataForSubmission(): array
    {
        return $this->data()->map(function ($value, $key) {
            $field = $this->fields->get($key);

            // Return early if a field can't be found, else we'll run into errors with the below code.
            if (is_null($field)) {
                return null;
            }

            // Only keep values of fields that should be submitted, e.g. if 'always_save' is on.
            if (! $this->fieldsToSubmit->get($field->handle)) {
                return null;
            }

            return $field->process($value);
        })->all();
    }

    protected function temporaryUploadedFiles(): array
    {
        return $this->data()
            ->whereInstanceOf(TemporaryUploadedFile::class)
            ->all();
    }
}
