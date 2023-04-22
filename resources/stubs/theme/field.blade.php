@if ($field->show)

    <div
        wire:key="wire_{{ $field->id }}"
        class="col-span-1
            {{ $field->width === 25 ? 'md:col-span-3' : '' }}
            {{ $field->width === 33 ? 'md:col-span-4' : '' }}
            {{ $field->width === 50 ? 'md:col-span-6' : '' }}
            {{ $field->width === 66 ? 'md:col-span-8' : '' }}
            {{ $field->width === 75 ? 'md:col-span-9' : '' }}
            {{ $field->width === 100 ? 'md:col-span-12' : '' }}
        "
    >
            @include($field->view)
    </div>

@endif
