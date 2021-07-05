@if($withCaptcha)

    @section('captcha')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endsection

    <div
        class="g-recaptcha"
        data-sitekey="@captchaKey"
        data-callback="setResponseToken_{@captchaId}"
        data-expired-callback="resetResponseToken_{@captchaId}"
        wire:ignore
    ></div>

    <script>
        function setResponseToken_{@captchaId}(token) {
            @this.set('captcha', token)
        }

        function resetResponseToken_{@captchaId}() {
            @this.set('captcha', null)
        }
    </script>

@endif
