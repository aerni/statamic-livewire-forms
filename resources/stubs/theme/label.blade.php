<label
    for="{{ $field->id }}"
    id="{{ $field->id }}-label"
    class="block text-sm font-medium text-gray-700 {{ ! $field->show_label ? 'sr-only' : '' }}"
>
    {{ $field->label }}
</label>
