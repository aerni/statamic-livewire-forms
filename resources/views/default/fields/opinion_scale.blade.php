<fieldset>
    <div class="flex flex-col gap-y-3">
        @formView('messages.legend')

        @if($field->instructions && $field->instructions_position === 'above')
            @formView('messages.instructions')
        @endif

        <div class="flex flex-col gap-y-2">
            <div class="flex items-start flex-wrap gap-2">
                @foreach($field->scale_values as $value)
                    <div wire:key="{{ $field->id }}-{{ $value }}">
                        <input
                            id="{{ $field->id }}-{{ $value }}"
                            name="{{ $field->id }}"
                            value="{{ $value }}"
                            type="radio"
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
                            for="{{ $field->id }}-{{ $value }}"
                            class="flex items-center justify-center w-10 h-10 text-sm font-medium cursor-pointer border rounded-md peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white peer-focus:ring-2 peer-focus:ring-indigo-500 {{ $errors->has($field->key) ? 'text-red-800 border-red-300' : 'text-gray-900 border-gray-300' }}"
                        >
                            {{ $value }}
                        </label>
                    </div>
                @endforeach
            </div>

            @if($field->low_label || $field->middle_label || $field->high_label)
                <div class="flex justify-between text-[13px] text-gray-500">
                    <span>{{ $field->low_label }}</span>
                    <span>{{ $field->middle_label }}</span>
                    <span>{{ $field->high_label }}</span>
                </div>
            @endif
        </div>

        @if($errors->has($field->key))
            @formView('messages.error')
        @elseif($field->instructions && $field->instructions_position === 'below')
            @formView('messages.instructions')
        @endif
    </div>
</fieldset>
