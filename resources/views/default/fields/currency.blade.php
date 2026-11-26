@formView('messages.display')

@if($field->instructions && $field->instructions_position === 'above')
    @formView('messages.instructions')
@endif

<div
    @class([
        'flex items-center rounded-md bg-white px-3 border focus-within:ring-1',
        'border-red-300 focus-within:border-red-500 focus-within:ring-red-500' => $errors->has($field->key),
        'border-gray-300 focus-within:border-indigo-500 focus-within:ring-indigo-500' => ! $errors->has($field->key),
    ])
>
    @if($field->prepend && $field->prepend !== $field->currency)
        <div class="shrink-0 text-gray-500 select-none sm:text-sm">{{ $field->prepend }}</div>
    @endif

    <input
        id="{{ $field->id }}"
        name="{{ $field->id }}"
        type="text"
        inputmode="decimal"
        placeholder="{{ $field->currency_placeholder }}"

        @if($field->autocomplete)
            autocomplete="{{ $field->autocomplete }}"
        @endif

        @if($field->wire_model)
            wire:model.{{ $field->wire_model }}="{{ $field->key }}"
        @else
            wire:model="{{ $field->key }}"
        @endif

        @if($errors->has($field->key))
            class="block min-w-0 grow border-0 bg-transparent py-2 pr-3 text-red-900 placeholder-red-400 focus:ring-0 sm:text-sm {{ $field->prepend && $field->prepend !== $field->currency ? 'pl-1' : 'pl-0' }}"
            aria-invalid="true"
            aria-describedby="{{ collect([$field->currency ? $field->id.'-currency' : null, $field->id.'-error'])->filter()->implode(' ') }}"
        @else
            class="block min-w-0 grow border-0 bg-transparent py-2 pr-3 text-gray-900 placeholder-gray-400 focus:ring-0 sm:text-sm {{ $field->prepend && $field->prepend !== $field->currency ? 'pl-1' : 'pl-0' }}"
            @if($field->currency || $field->instructions)
                aria-describedby="{{ collect([$field->currency ? $field->id.'-currency' : null, $field->instructions ? $field->id.'-instructions' : null])->filter()->implode(' ') }}"
            @endif
        @endif
    />

    @if($field->currency)
        <div id="{{ $field->id }}-currency" class="shrink-0 text-gray-500 select-none sm:text-sm">{{ $field->currency }}</div>
    @endif
</div>

@if($errors->has($field->key))
    @formView('messages.error')
@elseif($field->instructions && $field->instructions_position === 'below')
    @formView('messages.instructions')
@endif
