<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Statamic\Support\Str;
use Illuminate\Support\Arr;

trait WithData
{
    public array $data = [];

    public function mountWithData(): void
    {
        $this->data = $this->defaultData();
    }

    protected function defaultData(): array
    {
        return collect($this->data)
            ->only($this->fields->captcha()?->handle ?? []) // Make sure to preserve the captcha response.
            ->merge($this->fields->defaultValues())
            ->all();
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

    // TODO: Can we move this into the normalizedDataForSubmission method?
    protected function uploadedFiles(): array
    {
        // Only get the asset fields that contain data.
        $assetFields = array_intersect_key($this->data, $this->fields->getByType('assets')->all());

        // The assets fieldtype is expecting an array, even for `max_files: 1`, but we don't want to force that on the front end.
        return collect($assetFields)
            ->map(fn ($field) => Arr::wrap($field))
            ->all();
    }
}
