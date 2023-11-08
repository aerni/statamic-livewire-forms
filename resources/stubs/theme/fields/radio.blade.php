<fieldset class="space-y-3">
    <div>
        @include($this->component->getView('legend'))

        @if($field->instructions_position == 'above')
            @include($this->component->getView('instructions'))
        @endif
    </div>

    <div class="flex {{ $field->inline ? 'items-start space-x-6' : 'flex-col space-y-3' }}">
        @foreach($field->options as $option => $label)
            <div wire:key="{{ $field->id }}-{{ $option }}" class="flex items-start">
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
                <div class="ml-3 text-sm">
                    <label
                        for="{{ $field->id }}-{{ $option }}"
                        class="font-medium {{ $errors->has($field->key) ? 'text-red-700' : 'text-gray-700' }}"
                    >
                        {{ $label }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    <div>
        @if($field->instructions_position == 'below')
            @include($this->component->getView('instructions'))
        @endif

        @include($this->component->getView('error'))
    </div>
</fieldset>
