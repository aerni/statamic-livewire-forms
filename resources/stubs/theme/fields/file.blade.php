@if ($field->show_label)
    <div class="mb-3">
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
    <input
        id="{{ $field->id }}"
        name="{{ $field->id }}"
        type="file"
        wire:model.{{ $field->wire_model_modifier }}="{{ $field->key }}"
        @if ($field->multiple) multiple @endif

        @if (! $errors->has($field->key))
            class="block w-full"
        @else
            class="block w-full text-red-800"
            aria-invalid="true"
            aria-describedby="{{ $field->id }}-error"
        @endif

    />
</div>

@include($this->component->getView('error'))
