<div>
    @formView('messages.display')

    @if($field->instructions && $field->instructions_position === 'above')
        @formView('messages.instructions')
    @endif
</div>

<input
    id="{{ $field->id }}"
    name="{{ $field->id }}"
    type="{{ $field->input_type }}"

    @if($field->placeholder)
        placeholder="{{ $field->placeholder }}"
    @endif

    @if($field->autocomplete)
        autocomplete="{{ $field->autocomplete }}"
    @endif

    @if($field->character_limit)
        maxlength="{{ $field->character_limit }}"
    @endif

    @if($field->wire_model)
        wire:model.{{ $field->wire_model }}="{{ $field->key }}"
    @else
        wire:model="{{ $field->key }}"
    @endif

    @if(! $errors->has($field->key))
        class="block w-full text-gray-900 placeholder-gray-400 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        @if($field->instructions)
            aria-describedby="{{ $field->id }}-instructions"
        @endif
    @else
        class="block w-full text-red-900 placeholder-red-400 border-red-300 rounded-md focus:ring-red-500 focus:border-red-500 sm:text-sm"
        aria-invalid="true"
        aria-describedby="{{ $field->id }}-error"
    @endif
/>

@if($errors->has($field->key))
    @formView('messages.error')
@elseif($field->instructions && $field->instructions_position === 'below')
    @formView('messages.instructions')
@endif
