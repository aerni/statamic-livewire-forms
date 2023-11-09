<div>
    @include($this->component->getView('label'))

    @if($field->instructions_position == 'above')
        @include($this->component->getView('instructions'))
    @endif
</div>

<textarea
    id="{{ $field->id }}"
    name="{{ $field->id }}"
    placeholder="{{ $field->placeholder }}"

    @if($field->wire_model)
        wire:model.{{ $field->wire_model }}="{{ $field->key }}"
    @else
        wire:model="{{ $field->key }}"
    @endif

    @if($field->rows)
        rows="{{ $field->rows }}"
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
></textarea>

<div>
    @if($field->instructions_position == 'below')
        @include($this->component->getView('instructions'))
    @endif

    @include($this->component->getView('error'))
</div>
