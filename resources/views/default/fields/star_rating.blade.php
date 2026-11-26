@formView('messages.display')

@if($field->instructions && $field->instructions_position === 'above')
    @formView('messages.instructions')
@endif

<div
    id="{{ $field->id }}"
    x-data="{ value: @js((float) ($field->value ?? 0)), hover: null }"
    x-on:mouseleave="hover = null"
    x-on:form-success.window="value = 0"
    class="flex items-center gap-x-1"
    role="radiogroup"
    @if($errors->has($field->key))
        aria-invalid="true"
        aria-describedby="{{ $field->id }}-error"
    @elseif($field->instructions)
        aria-describedby="{{ $field->id }}-instructions"
    @endif
>
    @foreach(range(1, $field->max_stars) as $star)
        <div wire:key="{{ $field->id }}-{{ $star }}" class="relative w-6 h-6">
            <svg class="w-6 h-6 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 2l2.47 5.007 5.53.803-4 3.9.944 5.505L10 14.615l-4.944 2.6L6 11.71l-4-3.9 5.53-.803L10 2z" clip-rule="evenodd" />
            </svg>

            <div
                class="absolute inset-0 overflow-hidden text-yellow-400"
                x-bind:style="{ width: ((hover ?? value) >= {{ $star }} ? 100 : (hover ?? value) >= {{ $star - 0.5 }} ? 50 : 0) + '%' }"
            >
                <svg class="w-6 h-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 2l2.47 5.007 5.53.803-4 3.9.944 5.505L10 14.615l-4.944 2.6L6 11.71l-4-3.9 5.53-.803L10 2z" clip-rule="evenodd" />
                </svg>
            </div>

            @foreach($field->step == 0.5 ? [$star - 0.5, $star] : [$star] as $rating)
                <label
                    for="{{ $field->id }}-{{ $rating }}"
                    class="absolute inset-y-0 cursor-pointer {{ $field->step == 0.5 ? 'w-1/2 '.($loop->first ? 'left-0' : 'right-0') : 'inset-x-0' }}"
                    x-on:mouseenter="hover = {{ $rating }}"
                >
                    <span class="sr-only">{{ $rating }}</span>

                    <input
                        id="{{ $field->id }}-{{ $rating }}"
                        name="{{ $field->id }}"
                        value="{{ $rating }}"
                        type="radio"
                        class="sr-only"
                        x-on:change="value = {{ $rating }}"

                        @if($field->wire_model)
                            wire:model.{{ $field->wire_model }}="{{ $field->key }}"
                        @else
                            wire:model="{{ $field->key }}"
                        @endif
                    />
                </label>
            @endforeach
        </div>
    @endforeach
</div>

@if($errors->has($field->key))
    @formView('messages.error')
@elseif($field->instructions && $field->instructions_position === 'below')
    @formView('messages.instructions')
@endif
