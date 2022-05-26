@if ($field->show_label)
    <div class="mb-1">
        <label for="{{ $field->id }}" class="block font-medium text-gray-700 @if ($field->instructions) text-base @else text-sm @endif">
            {{ $field->label }}
        </label>
        @if ($field->instructions)
            <p class="mb-4 text-sm text-gray-500">{{ $field->instructions }}</p>
        @endif
    </div>
@else
    <label for="{{ $field->id }}" class="sr-only">{{ $field->label }}</label>
@endif

<div>
    <textarea
        id="{{ $field->id }}"
        name="{{ $field->id }}"
        placeholder="{{ $field->placeholder }}"
        wire:model.{{ $field->wire_model_modifier }}="{{ $field->key }}"
        rows="5"

        @if (! $errors->has($field->key))
            class="block w-full placeholder-gray-300 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        @else
            class="block w-full text-red-800 placeholder-red-300 border-red-300 rounded-md focus:ring-red-500 focus:border-red-500 sm:text-sm"
            aria-invalid="true"
            aria-describedby="{{ $field->id }}-error"
        @endif
    ></textarea>
</div>

@include($this->component->getView('error'))
