<div
    x-data="{
        show() {
            return @json($field->hidden) ? false : this.passesConditions()
        },
        passesConditions() {
            return Statamic.$conditions.showField({{ $field->conditions }}, $wire.data)
        },
    }"
    x-show="show"
    x-effect="$wire.$dispatchSelf('field-conditions-updated', { field: '{{ $field->handle }}', passesConditions: passesConditions() })"
    wire:key="{{ $field->id }}"
    class="space-y-2 col-span-1
        {{ $field->width === 25 ? 'md:col-span-3' : '' }}
        {{ $field->width === 33 ? 'md:col-span-4' : '' }}
        {{ $field->width === 50 ? 'md:col-span-6' : '' }}
        {{ $field->width === 66 ? 'md:col-span-8' : '' }}
        {{ $field->width === 75 ? 'md:col-span-9' : '' }}
        {{ $field->width === 100 ? 'md:col-span-12' : '' }}
    "
>
    @formView($field->view)
</div>
