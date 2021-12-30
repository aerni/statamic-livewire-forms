<div class="flex items-start">
    <div class="flex items-center h-5">
        <input
            id="{{ $field->id }}.{{ $option }}"
            name="{{ $field->id }}"
            value="{{ $option }}"
            type="radio"
            wire:model.lazy="{{ $field->key }}"

            @if (! $errors->has($field->key))
                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
            @else
                class="w-4 h-4 text-indigo-600 border-red-300 focus:ring-red-500"
                aria-invalid="true"
                aria-describedby="{{ $field->id }}-error"
            @endif
        />
    </div>
    <div class="ml-3 text-sm">
        <label
            for="{{ $field->id }}.{{ $option }}"
            class="font-medium {{ $errors->has($field->key) ? 'text-red-700' : 'text-gray-700'}}"
        >
            {{ $label }}
        </label>
    </div>
</div>
