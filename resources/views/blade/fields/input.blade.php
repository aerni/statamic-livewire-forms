<label for="{{ $field['handle'] }}" class="block text-sm font-medium text-gray-700">
    {{ $field['label'] }}
</label>

<div class="mt-1">
    <input
        id="{{ $field['handle'] }}"
        name="{{ $field['handle'] }}"
        type="{{ $field['input_type'] }}"
        autocomplete="{{ $field['autocomplete'] }}"
        placeholder="{{ $field['placeholder'] }}"
        wire:model.lazy="{{ $field['key'] }}"

        @if (! $errors->has($field['key']))
            class="block w-full placeholder-gray-300 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        @else
            class="block w-full text-red-800 placeholder-red-300 border-red-300 rounded-md focus:ring-red-500 focus:border-red-500 sm:text-sm"
            aria-invalid="true"
            aria-describedby="{{ $field['handle'] }}-error"
        @endif

    />
</div>

@include('livewire-forms::fields.error')
