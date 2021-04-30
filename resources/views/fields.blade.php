<div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-12">
    @foreach ($fields as $field)
        <div class="
            {{ $field->width === 25 ? 'md:col-span-3' : '' }}
            {{ $field->width === 33 ? 'md:col-span-4' : '' }}
            {{ $field->width === 50 ? 'md:col-span-6' : '' }}
            {{ $field->width === 66 ? 'md:col-span-8' : '' }}
            {{ $field->width === 75 ? 'md:col-span-9' : '' }}
            {{ $field->width === 100 ? 'md:col-span-12' : '' }}"
        ">
            @include('statamic-livewire-forms::fields.' . $field['type'])
        </div>
    @endforeach
</div>
