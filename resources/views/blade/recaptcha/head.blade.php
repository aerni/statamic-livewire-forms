@if ($hide_badge)
    <style>.grecaptcha-badge { visibility: collapse !important }</style>
@endif

@if ($invisible)
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var captchas = Array.prototype.slice.call(document.querySelectorAll('.g-recaptcha[data-size=invisible]'), 0);

        captchas.forEach(function (captcha, index) {
            var form = captcha.parentNode;
            while (form.tagName !== 'FORM') {
                form = form.parentNode;
            }
            // create custom callback
            window['recaptchaSubmit' + index] = function () { form.submit(); };
            captcha.setAttribute('data-callback', 'recaptchaSubmit' + index);
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                grecaptcha.reset(index);
                grecaptcha.execute(index);
            });
        });
    });
    </script>
@endif

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
