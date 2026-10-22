<fieldset>
    <div class="flex flex-col gap-y-3">
        @formView('messages.legend')

        @if($field->instructions && $field->instructions_position === 'above')
            @formView('messages.instructions')
        @endif

        <ol
            id="{{ $field->id }}"
            x-data="{
                move(from, to) {
                    const order = [...$wire.fields['{{ $field->handle }}'].value];
                    order.splice(to, 0, order.splice(from, 1)[0]);
                    $wire.set('{{ $field->key }}', order);
                }
            }"
            class="flex flex-col gap-y-2"
            @if($errors->has($field->key))
                aria-invalid="true"
                aria-describedby="{{ $field->id }}-error"
            @elseif($field->instructions)
                aria-describedby="{{ $field->id }}-instructions"
            @endif
        >
            @foreach($field->value ?? [] as $index => $key)
                <li
                    wire:key="{{ $field->id }}-{{ $key }}"
                    class="flex items-center justify-between gap-x-3 px-3 py-2 bg-white border border-gray-300 rounded-md"
                >
                    <span class="text-sm font-medium text-gray-900">
                        <span class="text-gray-400">{{ $index + 1 }}.</span>
                        {{ $field->options[$key] ?? $key }}
                    </span>

                    <span class="flex items-center gap-x-1">
                        <button
                            type="button"
                            class="p-1 text-gray-500 rounded hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-30"
                            aria-label="{{ __('Move up') }}"
                            x-on:click="move({{ $index }}, {{ $index - 1 }})"
                            @disabled($loop->first)
                        >
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <button
                            type="button"
                            class="p-1 text-gray-500 rounded hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-30"
                            aria-label="{{ __('Move down') }}"
                            x-on:click="move({{ $index }}, {{ $index + 1 }})"
                            @disabled($loop->last)
                        >
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </span>
                </li>
            @endforeach
        </ol>

        @if($errors->has($field->key))
            @formView('messages.error')
        @elseif($field->instructions && $field->instructions_position === 'below')
            @formView('messages.instructions')
        @endif
    </div>
</fieldset>
