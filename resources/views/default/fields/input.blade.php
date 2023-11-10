<div>
    @include($this->component->getView('label'))

    @if($field->instructions_position == 'above')
        @include($this->component->getView('instructions'))
    @endif
</div>

<input
    id="{{ $field->id }}"
    name="{{ $field->id }}"
    type="{{ $field->input_type }}"
    autocomplete="{{ $field->autocomplete }}"
    placeholder="{{ $field->placeholder }}"

    @if($field->wire_model)
        wire:model.{{ $field->wire_model }}="{{ $field->key }}"
    @else
        wire:model="{{ $field->key }}"
    @endif

    @if(! $errors->has($field->key))
        class="block w-full text-black placeholder-gray-300 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        @if($field->instructions)
            aria-describedby="{{ $field->id }}-instructions"
        @endif
    @else
        class="block w-full text-red-800 placeholder-red-300 border-red-300 rounded-md focus:ring-red-500 focus:border-red-500 sm:text-sm"
        aria-invalid="true"
        aria-describedby="{{ $field->id }}-error"
    @endif
/>

<div>
    @if($field->instructions_position == 'below')
        @include($this->component->getView('instructions'))
    @endif

    @include($this->component->getView('error'))
</div>