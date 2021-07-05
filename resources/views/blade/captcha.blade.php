@if($withCaptcha)

    @section('captcha')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endsection

    <div
        class="g-recaptcha"
        data-sitekey="@captchaKey"
        data-callback="setResponseToken"
        data-expired-callback="resetResponseToken"
        wire:ignore
    ></div>

    <script>
        function setResponseToken(token) {
            @this.set('captcha', token)
        }

        function resetResponseToken() {
            @this.set('captcha', null)
        }
    </script>

@endif
