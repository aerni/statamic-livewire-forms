<fieldset>
    <div class="flex flex-col gap-y-3">
        @formView('messages.legend')

        @if($field->instructions && $field->instructions_position === 'above')
            @formView('messages.instructions')
        @endif

        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
            @foreach($field->fields() as $groupField)
                <div
                    wire:key="{{ $groupField->id }}"
                    class="flex flex-col gap-y-2 col-span-1
                        {{ $groupField->width === 25 ? 'md:col-span-3' : '' }}
                        {{ $groupField->width === 33 ? 'md:col-span-4' : '' }}
                        {{ $groupField->width === 50 ? 'md:col-span-6' : '' }}
                        {{ $groupField->width === 66 ? 'md:col-span-8' : '' }}
                        {{ $groupField->width === 75 ? 'md:col-span-9' : '' }}
                        {{ $groupField->width === 100 ? 'md:col-span-12' : '' }}
                    "
                >
                    @formView($groupField->view, ['field' => $groupField])
                </div>
            @endforeach
        </div>

        @if($errors->has($field->key))
            @formView('messages.error')
        @elseif($field->instructions && $field->instructions_position === 'below')
            @formView('messages.instructions')
        @endif
    </div>
</fieldset>
