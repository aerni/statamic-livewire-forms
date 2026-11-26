<div class="flex flex-col gap-y-2">
    @formView('messages.display')

    @if($field->instructions && $field->instructions_position === 'above')
        @formView('messages.instructions')
    @endif

    <div class="flex items-center gap-x-3">
        <div
            class="group relative inline-flex w-11 shrink-0 rounded-full p-0.5 transition-colors duration-200 ease-in-out has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-offset-2
                {{ $errors->has($field->key)
                    ? 'bg-red-100 has-[:checked]:bg-red-600 has-[:focus-visible]:ring-red-500'
                    : 'bg-gray-200 has-[:checked]:bg-indigo-600 has-[:focus-visible]:ring-indigo-500' }}
            "
        >
            <span class="size-5 rounded-full bg-white shadow-sm ring-1 ring-gray-300 transition-transform duration-200 ease-in-out group-has-[:checked]:translate-x-5"></span>

            <input
                id="{{ $field->id }}"
                name="{{ $field->id }}"
                type="checkbox"
                class="absolute inset-0 size-full appearance-none opacity-0 focus:outline-none"

                @if($field->wire_model)
                    wire:model.{{ $field->wire_model }}="{{ $field->key }}"
                @else
                    wire:model="{{ $field->key }}"
                @endif

                @if(! $errors->has($field->key))
                    @if($field->instructions)
                        aria-describedby="{{ $field->id }}-instructions"
                    @endif
                @else
                    aria-invalid="true"
                    aria-describedby="{{ $field->id }}-error"
                @endif
            />
        </div>

        @if($field->inline_label)
            <div class="text-sm leading-5">
                <label
                    for="{{ $field->id }}"
                    class="font-medium [&_a]:text-indigo-500 [&_a]:hover:text-indigo-700 [&_a]:focus-within:outline-none [&_a]:focus-within:ring-2 [&_a]:focus-within:ring-indigo-500 [&_a]:focus-within:rounded-sm {{ $errors->has($field->key) ? 'text-red-800' : 'text-gray-900' }}"
                >
                    {!! Statamic::modify($field->inline_label)->markdown() !!}
                </label>
            </div>
        @endif
    </div>

    @if($errors->has($field->key))
        @formView('messages.error')
    @elseif($field->instructions && $field->instructions_position === 'below')
        @formView('messages.instructions')
    @endif
</div>
