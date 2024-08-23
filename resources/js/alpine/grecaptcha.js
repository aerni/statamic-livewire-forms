export default (config) => ({
    init() {
        if (typeof window.grecaptchaIsReady === 'undefined') {
            return setTimeout(() => this.init(), 100)
        }

        window.grecaptcha.render(this.$el, {
            'sitekey': config.siteKey,
            'callback': (token) => this.$wire.set(config.field, token),
            'expired-callback': () => this.$wire.set(config.field, null),
        })
    },
})
