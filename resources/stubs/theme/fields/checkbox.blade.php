<fieldset>
    @if ($field->show_label)
        <div class="mb-3">
            <legend class="font-medium text-gray-700 @if ($field->instructions) text-base @else text-sm @endif">{{ $field->label }}</legend>
            @if ($field->instructions)
                <p class="mb-4 text-sm text-gray-500">{{ $field->instructions }}</p>
            @endif
        </div>
    @else
        <legend class="sr-only">{{ $field->label }}</legend>
    @endif

    <div>
        <div class="{{ $field->inline ? 'items-start space-x-6' : 'flex-col space-y-3' }} flex">
            @foreach ($field->options as $option => $label)
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="{{ $field->id }}.{{ $option }}"
                            name="{{ $field->id }}"
                            value="{{ $option }}"
                            type="checkbox"
                            wire:model.{{ $field->wire_model_modifier }}="{{ $field->key }}"

                            @if (! $errors->has($field->key))
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            @else
                                class="w-4 h-4 text-indigo-600 border-red-300 rounded focus:ring-red-500"
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
            @endforeach
        </div>

        @include($this->component->getView('error'))
    </div>
</fieldset>
