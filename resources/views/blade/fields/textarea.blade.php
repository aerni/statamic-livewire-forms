<label for="{{ $field['handle'] }}">
    {{ $field['label'] }}
</label>

<div>
    <textarea
        id="{{ $field['handle'] }}"
        name="{{ $field['handle'] }}"
        placeholder="{{ $field['placeholder'] }}"
        wire:model.lazy="{{ $field['key'] }}"

        @if (! $errors->has($field['key']))
            class=""
        @else
            class=""
            aria-invalid="true"
            aria-describedby="{{ $field['handle'] }}-error"
        @endif
    ></textarea>
</div>

@include('statamic-livewire-forms::fields.error')
