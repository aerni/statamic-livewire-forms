@formView('messages.display')

@if($field->instructions && $field->instructions_position === 'above')
    @formView('messages.instructions')
@endif

<div
    id="{{ $field->id }}"
    class="g-recaptcha"
    data-sitekey="@captchaKey"
    data-callback="setResponseToken_{{ $this->getId() }}"
    data-expired-callback="resetResponseToken_{{ $this->getId() }}"
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endassets

<script>
    function setResponseToken_{{ $this->getId() }}(token) {
        @this.set('{{ $field->key }}', token)
    }

    function resetResponseToken_{{ $this->getId() }}() {
        @this.set('{{ $field->key }}', null)
    }
</script>
