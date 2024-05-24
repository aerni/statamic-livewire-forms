<fieldset>
    <div class="flex flex-col gap-y-3">
        @formView('messages.legend')

        @if($field->instructions && $field->instructions_position === 'above')
            @formView('messages.instructions')
        @endif

        <div class="{{ $field->inline ? 'flex items-start flex-wrap gap-x-6 gap-y-2' : 'flex flex-col gap-y-2' }}">
            @foreach($field->options as $option => $label)
                <div wire:key="{{ $field->id }}-{{ $option }}" class="flex items-start gap-x-3">
                    <div class="flex items-center h-5">
                        <input
                            id="{{ $field->id }}-{{ $option }}"
                            name="{{ $field->id }}"
                            value="{{ $option }}"
                            type="radio"

                            @if($field->wire_model)
                                wire:model.{{ $field->wire_model }}="{{ $field->key }}"
                            @else
                                wire:model="{{ $field->key }}"
                            @endif

                            @if(! $errors->has($field->key))
                                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                @if($field->instructions)
                                    aria-describedby="{{ $field->id }}-instructions"
                                @endif
                            @else
                                class="w-4 h-4 text-indigo-600 border-red-300 focus:ring-red-500"
                                aria-invalid="true"
                                aria-describedby="{{ $field->id }}-error"
                            @endif
                        />
                    </div>

                    <div class="text-sm leading-5">
                        <label
                            for="{{ $field->id }}-{{ $option }}"
                            class="font-medium {{ $errors->has($field->key) ? 'text-red-800' : 'text-gray-900' }}"
                        >
                            {{ $label }}
                        </label>
                    </div>
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
