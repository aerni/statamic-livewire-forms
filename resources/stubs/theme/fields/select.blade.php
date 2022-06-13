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
    <select
        id="{{ $field->id }}"
        name="{{ $field->id }}"
        wire:model.{{ $field->wire_model_modifier }}="{{ $field->key }}"
        @if ($field->multiple) multiple @endif

        @if (! $errors->has($field->key))
            class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        @else
            class="block w-full py-2 pl-3 pr-10 text-base text-red-800 border-red-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
            aria-invalid="true"
            aria-describedby="{{ $field->id }}-error"
        @endif
    >
        @if ($field->placeholder && ! $field->multiple)
            <option value="" selected>{{ $field->placeholder }}</option>
        @endif

        @foreach ($field->options as $option => $label)
            <option value="{{ $option }}">{{ $label }}</option>
        @endforeach
    </select>
</div>

@include($this->component->getView('error'))
