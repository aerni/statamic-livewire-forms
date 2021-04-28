<div>

    <div>
        <label for="{{ $field->handle }}">
            {{ $field->label }}
        </label>
    </div>

    <div>
        <input
            name="{{ $field->handle }}"
            id="{{ $field->handle }}"
            type="{{ $field->input_type }}"
            autocomplete="{{ $field->autocomplete }}"
            placeholder="{{ $field->placeholder }}"
            wire:model.lazy="{{ $field->key }}"
        />

        @include('statamic-livewire-forms::fields.error')
    </div>

</div>
