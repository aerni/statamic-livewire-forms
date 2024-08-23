@formView('messages.display')

@if($field->instructions && $field->instructions_position === 'above')
    @formView('messages.instructions')
@endif

<div
    x-data="grecaptcha"
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
        window.grecaptchaOnloadCallback = function() {
            window.grecaptchaIsReady = true
        }
    </script>
    <script async defer src="https://www.google.com/recaptcha/api.js?onload=grecaptchaOnloadCallback&render=explicit"></script>
@endassets

@script
    <script>
        Alpine.data('grecaptcha', () => {
            return {
                init() {
                    if (typeof window.grecaptchaIsReady === 'undefined') {
                        return setTimeout(() => this.init(), 100)
                    }

                    grecaptcha.render(this.$el, {
                        'sitekey': '@captchaKey',
                        'callback': (token) => $wire.set('{{ $field->key }}', token),
                        'expired-callback': () => $wire.set('{{ $field->key }}', null),
                    })
                },
            }
        })
    </script>
@endscript
