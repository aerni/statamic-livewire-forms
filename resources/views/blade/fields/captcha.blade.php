@if ($field['show_label'])
    <div class="mb-1">
        <p class="block font-medium text-gray-700 @if ($field['instructions']) text-base @else text-sm @endif">
            {{ $field['label'] }}
        </p>
        @if ($field['instructions'])
            <p class="mb-4 text-sm text-gray-500">{{ $field['instructions'] }}</p>
        @endif
    </div>
@endif

<div
    class="g-recaptcha"
    data-sitekey="@captchaKey"
    data-callback="setResponseToken_{{ $_instance->id }}"
    data-expired-callback="resetResponseToken_{{ $_instance->id }}"
    wire:ignore
></div>

@include('livewire-forms::fields.error')

<script>
    function setResponseToken_{{ $_instance->id }}(token) {
        @this.set('{{ $field['key'] }}', token)
    }

    function resetResponseToken_{{ $_instance->id }}() {
        @this.set('{{ $field['key'] }}', null)
    }
</script>

@once
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endonce
