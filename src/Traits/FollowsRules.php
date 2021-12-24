<?php

namespace Aerni\LivewireForms\Traits;

use Illuminate\Support\Str;

trait FollowsRules
{
    protected function rules(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field['key'] => $field['rules']];
        })->toArray();
    }

    protected function realtimeRules($field): array
    {
        $field = $this->fields->get(Str::remove('data.', $field));

        // Don't use realtime validation for the honeypot.
        if ($field['type'] === 'honeypot') {
            return [$field['key'] => []];
        };

        // Don't use realtime validation for the captcha.
        if ($field['type'] === 'captcha') {
            return [$field['key'] => []];
        }

        // Get the realtime validation config from the field, form blueprint or global config.
        $realtime = $field['realtime']
            // Would like to get the realtime config from the form config instead of the form blueprint, but there's currently no way to access custom data.
            ?? $this->form->blueprint()->contents()['sections']['main']['realtime']
            ?? config('livewire-forms.realtime', true);

        // Disable realtime validation if "realtime: false".
        if (! $realtime) {
            return [$field['key'] => []];
        }

        // Use the field validation rules if "realtime: true".
        if ($realtime === true) {
            return [$field['key'] => $field['rules']];
        }

        // Make sure to always get an array of realtime rules.
        $realtimeRules = is_array($realtime) ? $realtime : explode('|', $realtime);

        // Remove any realtime rules that are not part of the validation rules.
        $realtimeRules = array_intersect($realtimeRules, $field['rules']);

        return [$field['key'] => $realtimeRules];
    }

    protected function validationAttributes(): array
    {
        return $this->fields->mapWithKeys(function ($field) {
            return [$field['key'] => $field['label']];
        })->toArray();
    }
}
