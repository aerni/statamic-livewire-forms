<label for="{{ $field['handle'] }}" class="">
    {{ $field['label'] }}
</label>

<div class="">
    <input
        id="{{ $field['handle'] }}"
        name="{{ $field['handle'] }}"
        type="{{ $field['input_type'] }}"
        autocomplete="{{ $field['autocomplete'] }}"
        placeholder="{{ $field['placeholder'] }}"
        wire:model.lazy="{{ $field['key'] }}"

        @if (! $errors->has($field['key']))
            class=""
        @else
            class=""
            aria-invalid="true"
            aria-describedby="{{ $field['handle'] }}-error"
        @endif

    />
</div>

@include('statamic-livewire-forms::fields.error')
