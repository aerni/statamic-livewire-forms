<div>
    @formView('messages.label')

    @if($field->instructions_position == 'above')
        @formView('messages.instructions')
    @endif
</div>

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

<div>
    @if($field->instructions_position == 'below')
        @formView('messages.instructions')
    @endif

    @formView('messages.error')
</div>

<script>
    function setResponseToken_{{ $this->getId() }}(token) {
        @this.set('{{ $field->key }}', token)
    }

    function resetResponseToken_{{ $this->getId() }}() {
        @this.set('{{ $field->key }}', null)
    }
</script>

@once
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endonce
