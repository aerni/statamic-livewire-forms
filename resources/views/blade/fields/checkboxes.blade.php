<fieldset>
    @if ($field['show_label'])
        <div class="mb-4">
            <legend class="text-base font-medium text-gray-900">{{ $field['label'] }}</legend>
            <p class="text-sm text-gray-500">{{ $field['instructions'] }}</p>
        </div>
    @endif
    <div>
        <div class="{{ $field['inline'] ? 'items-center space-x-6' : 'flex-col justify-center space-y-4' }} flex">
            @foreach ($field['options'] as $option => $label)
                @include('livewire-forms::fields/checkbox')
            @endforeach
        </div>

        @include('livewire-forms::fields.error')
    </div>
</fieldset>
