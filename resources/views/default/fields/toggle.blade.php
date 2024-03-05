<div class="flex items-start gap-x-3">
    <div class="flex items-center h-5">
        <input
            id="{{ $field->id }}"
            name="{{ $field->id }}"
            type="checkbox"

            @if($field->wire_model)
                wire:model.{{ $field->wire_model }}="{{ $field->key }}"
            @else
                wire:model="{{ $field->key }}"
            @endif

            @if(! $errors->has($field->key))
                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
            @else
                class="w-4 h-4 text-indigo-600 border-red-300 rounded focus:ring-red-500"
                aria-invalid="true"
                aria-describedby="{{ $field->id }}-error"
            @endif
        />
    </div>

    <div class="text-sm leading-5">
        <label
            for="{{ $field->id }}"
            class="font-medium [&_a]:text-indigo-500 [&_a]:hover:text-indigo-700 [&_a]:focus-within:outline-none [&_a]:focus-within:ring-2 [&_a]:focus-within:ring-indigo-500 [&_a]:focus-within:rounded-sm {{ $errors->has($field->key) ? 'text-red-800' : 'text-gray-900' }}"
        >
            {!! Statamic::modify($field->inline_label)->markdown() !!}
        </label>
    </div>
</div>

@formView('messages.error')
