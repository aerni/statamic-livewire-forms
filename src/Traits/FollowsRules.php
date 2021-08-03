<?php

namespace Aerni\LivewireForms\Traits;

use Illuminate\Support\Str;

trait FollowsRules
{
    protected function rules(): array
    {
        return $this->fields()->mapWithKeys(function ($field) {
            return [$field['key'] => $field['rules']];
        })->toArray();
    }

    protected function realtimeRules($field): array
    {
        // TODO: Validate honeypot but with this rule: https://laravel.com/docs/8.x/validation#rule-prohibited
        // Don't validate the honeypot.
        if ($field === $this->honeypot()['key']) {
            return [$this->honeypot()['key'] => []];
        };

        $field = $this->fields()[Str::remove('data.', $field)];

        // Don't use real-time validation for the captcha.
        if ($field['type'] === 'captcha') {
            return [$field['key'] => []];
        }

        // Get the realtime validation config from the field, form blueprint or global config.
        $realtime = $field['realtime']
            ?? $this->form->blueprint()->contents()['sections']['main']['realtime']
            ?? config('livewire-forms.realtime', true);

        // Disable realtime validation if "realtime: false".
        if (! $realtime) {
            return [$field['key'] => []];
        }

        // Use regular validation rules if "realtime: true".
        if ($realtime === true) {
            return [$field['key'] => $field['rules']];
        }

        // Make sure to always get an array of realtime rules.
        $realtimeRules = is_array($realtime) ? $realtime : explode('|', $realtime);

        // Remove any realtime rules that are not part of the validation rules.
        $realtimeRules = array_intersect($realtimeRules, $field['rules']);

        return [$field['key'] => $realtimeRules];
    }
}
