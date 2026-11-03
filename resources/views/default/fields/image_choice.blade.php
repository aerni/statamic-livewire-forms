@php($columns = ['sm:grid-cols-1', 'sm:grid-cols-2', 'sm:grid-cols-3', 'sm:grid-cols-4'][$field->columns - 1] ?? 'sm:grid-cols-3')

<fieldset>
    <div class="flex flex-col gap-y-3">
        @formView('messages.legend')

        @if($field->instructions && $field->instructions_position === 'above')
            @formView('messages.instructions')
        @endif

        <div class="grid grid-cols-2 gap-3 {{ $columns }}">
            @foreach($field->options as $option)
                <div wire:key="{{ $field->id }}-{{ $option['key'] }}">
                    <input
                        id="{{ $field->id }}-{{ $option['key'] }}"
                        name="{{ $field->id }}"
                        value="{{ $option['key'] }}"
                        type="{{ $field->multiple ? 'checkbox' : 'radio' }}"
                        class="sr-only peer"

                        @if($field->wire_model)
                            wire:model.{{ $field->wire_model }}="{{ $field->key }}"
                        @else
                            wire:model="{{ $field->key }}"
                        @endif

                        @if($errors->has($field->key))
                            aria-invalid="true"
                            aria-describedby="{{ $field->id }}-error"
                        @elseif($field->instructions)
                            aria-describedby="{{ $field->id }}-instructions"
                        @endif
                    />

                    <label
                        for="{{ $field->id }}-{{ $option['key'] }}"
                        class="flex flex-col gap-y-2 p-2 cursor-pointer border-2 rounded-md peer-checked:border-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-500 {{ $errors->has($field->key) ? 'border-red-300' : 'border-gray-200' }}"
                    >
                        @if($option['image'])
                            <img src="{{ $option['image'] }}" alt="" class="object-cover w-full rounded" style="aspect-ratio: {{ $field->aspect_ratio ?? '1 / 1' }}" />
                        @endif

                        <span class="flex items-center gap-x-2 text-sm font-medium text-gray-900">
                            <span class="flex items-center justify-center w-5 h-5 text-xs border border-gray-300 rounded">
                                {{ $option['letter'] }}
                            </span>
                            {{ $option['label'] }}
                        </span>
                    </label>
                </div>
            @endforeach
        </div>

        @if($errors->has($field->key))
            @formView('messages.error')
        @elseif($field->instructions && $field->instructions_position === 'below')
            @formView('messages.instructions')
        @endif
    </div>
</fieldset>
