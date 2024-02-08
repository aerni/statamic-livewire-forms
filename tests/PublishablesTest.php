<?php

it('publishes assets after install', function () {
    Artisan::call('statamic:install');

    expect(file_exists(public_path('vendor/livewire-forms/js/livewire-forms.js')))->toBeTrue();
});
