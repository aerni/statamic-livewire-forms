<label
    for="{{ $field->id }}"
    id="{{ $field->id }}-label"
    class="{{ $field->hide_display ? 'sr-only' : 'block text-sm font-medium text-gray-700 [&_+_p]:-mt-2' }}"
>
    {{ $field->display }}
</label>
