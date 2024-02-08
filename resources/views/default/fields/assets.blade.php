<div>
    @formView('messages.label')

    @if($field->instructions && $field->instructions_position === 'above')
        @formView('messages.instructions')
    @endif
</div>

<input
    id="{{ $field->id }}"
    name="{{ $field->id }}"
    type="file"

    @if($field->multiple)
        multiple
    @endif

    @if($field->wire_model)
        wire:model.{{ $field->wire_model }}="{{ $field->key }}"
    @else
        wire:model="{{ $field->key }}"
    @endif

    @if(! $errors->has($field->key))
        class="block w-full"
        @if($field->instructions)
            aria-describedby="{{ $field->id }}-instructions"
        @endif
    @else
        class="block w-full text-red-900"
        aria-invalid="true"
        aria-describedby="{{ $field->id }}-error"
    @endif
/>

@if($errors->has($field->key))
    @formView('messages.error')
@elseif($field->instructions && $field->instructions_position === 'below')
    @formView('messages.instructions')
@endif
