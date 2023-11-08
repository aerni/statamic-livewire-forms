<fieldset class="space-y-3">
    <div>
        @include($this->component->getView('legend'))

        @if($field->instructions_position == 'above')
            @include($this->component->getView('instructions'))
        @endif
    </div>

    <div class="flex items-start">
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
                    @if($field->instructions)
                        aria-describedby="{{ $field->id }}-instructions"
                    @endif
                @else
                    class="w-4 h-4 text-indigo-600 border-red-300 rounded focus:ring-red-500"
                    aria-invalid="true"
                    aria-describedby="{{ $field->id }}-error"
                @endif
            />
        </div>
        <div class="ml-3 text-sm">
            <label
                for="{{ $field->id }}"
                class="font-medium [&_a]:text-indigo-500 [&_a]:hover:text-indigo-700 [&_a]:focus-within:outline-none [&_a]:focus-within:ring-2 [&_a]:focus-within:ring-indigo-500 [&_a]:focus-within:rounded-sm {{ $errors->has($field->key) ? 'text-red-700' : 'text-gray-700' }}"
            >
                {!! Statamic::modify($field->inline_label)->markdown() !!}
            </label>
        </div>
    </div>

    <div>
        @if($field->instructions_position == 'below')
            @include($this->component->getView('instructions'))
        @endif

        @include($this->component->getView('error'))
    </div>
</fieldset>
