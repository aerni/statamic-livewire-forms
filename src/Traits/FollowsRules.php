<?php

namespace Aerni\StatamicLivewireForms\Traits;

use Illuminate\Support\Str;

trait FollowsRules
{
    public function rules(): array
    {
        return $this->fields()->mapWithKeys(function ($field) {
            return [$field->key => $field->rules];
        })->toArray();
    }

    protected function realtimeRules($field): array
    {
        $field = $this->fields()[Str::remove('data.', $field)];

        // Don't validate in realtime if the key is not set
        if (! $field->realtime) {
            return [$field->key => []];
        }

        // Use regular validation rules if "realtime: true"
        if ($field->realtime === true) {
            return [$field->key => $field->rules];
        }

        // Make sure to always get an array of realtime rules
        $realtimeRules = is_array($field->realtime) ? $field->realtime : explode('|', $field->realtime);

        // Remove any realtime rules that are not part of the field rules
        $realtimeRules = array_intersect($realtimeRules, $field->rules);

        return [$field->key => $realtimeRules];
    }
}
