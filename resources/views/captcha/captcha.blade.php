@if ($with_captcha)

    <div
        class="g-recaptcha"
        data-sitekey="@captchaKey"
        data-callback="setResponseToken_{{ $_instance->id }}"
        data-expired-callback="resetResponseToken_{{ $_instance->id }}"
        wire:ignore
    ></div>

    <script>
        function setResponseToken_{{ $_instance->id }}(token) {
            @this.set('captcha', token)
        }

        function resetResponseToken_{{ $_instance->id }}() {
            @this.set('captcha', null)
        }
    </script>

    @section('captchaScripts')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endsection

@endif
