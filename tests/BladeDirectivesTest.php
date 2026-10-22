<?php

use Illuminate\Support\Facades\Blade;

it('compiles the captcha field view when the site key is unset', function () {
    config(['livewire-forms.captcha.key' => null]);

    $compiled = Blade::compileString(
        file_get_contents(__DIR__.'/../resources/views/default/fields/captcha.blade.php')
    );

    expect($compiled)->toContain('Captcha::key()');
});

it('prints the captcha site key when the view renders', function () {
    config(['livewire-forms.captcha.key' => 'site-key']);

    expect(Blade::render('@captchaKey'))->toBe('site-key');
});
