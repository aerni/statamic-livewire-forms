<div>
    @include($this->component->getView('messages.label'))

    @if($field->instructions_position == 'above')
        @include($this->component->getView('messages.instructions'))
    @endif
</div>

<select
    id="{{ $field->id }}"
    name="{{ $field->id }}"

    @if($field->wire_model)
        wire:model.{{ $field->wire_model }}="{{ $field->key }}"
    @else
        wire:model="{{ $field->key }}"
    @endif

    @if($field->multiple)
        multiple
    @endif

    @if(! $errors->has($field->key))
        class="block w-full py-2 pl-3 pr-10 text-base text-black border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        @if($field->instructions)
            aria-describedby="{{ $field->id }}-instructions"
        @endif
    @else
        class="block w-full py-2 pl-3 pr-10 text-base text-red-800 border-red-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
        aria-invalid="true"
        aria-describedby="{{ $field->id }}-error"
    @endif
>
    @if($field->placeholder && ! $field->multiple)
        <option value="" selected>
            {{ $field->placeholder }}
        </option>
    @endif

    @foreach($field->options as $option => $label)
        <option wire:key="{{ $field->id }}-{{ $option }}" value="{{ $option }}">
            {{ $label }}
        </option>
    @endforeach
</select>

<div>
    @if($field->instructions_position == 'below')
        @include($this->component->getView('messages.instructions'))
    @endif

    @include($this->component->getView('messages.error'))
</div>
