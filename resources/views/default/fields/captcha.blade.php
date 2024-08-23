@formView('messages.display')

@if($field->instructions && $field->instructions_position === 'above')
    @formView('messages.instructions')
@endif

<div
    x-data="grecaptcha({
        field: '{{ $field->key }}',
        siteKey: '@captchaKey',
    })"
    id="{{ $field->id }}"
    class="g-recaptcha"
    wire:ignore
    aria-label="{{ $field->id }}-label"
    @if($field->instructions)
        aria-describedby="{{ $field->id }}-instructions"
    @endif
></div>

@if($errors->has($field->key))
    @formView('messages.error')
@elseif($field->instructions && $field->instructions_position === 'below')
    @formView('messages.instructions')
@endif

@assets
    <script>
        window.grecaptchaOnloadCallback = () => window.grecaptchaIsReady = true
    </script>
    <script async defer src="https://www.google.com/recaptcha/api.js?onload=grecaptchaOnloadCallback&render=explicit"></script>
@endassets
