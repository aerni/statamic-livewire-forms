<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('publishes assets after install', function () {
    Artisan::call('statamic:install');

    expect(file_exists(public_path('vendor/livewire-forms/css/filepond.css')))->toBeTrue();
    expect(file_exists(public_path('vendor/livewire-forms/js/filepond.js')))->toBeTrue();
    expect(file_exists(public_path('vendor/livewire-forms/js/form.js')))->toBeTrue();
    expect(file_exists(public_path('vendor/livewire-forms/js/grecaptcha.js')))->toBeTrue();
    expect(file_exists(public_path('vendor/livewire-forms/js/livewire-forms.js')))->toBeTrue();

    File::delete(public_path('vendor/livewire-forms/css/filepond.css'));
    File::delete(public_path('vendor/livewire-forms/js/filepond.js'));
    File::delete(public_path('vendor/livewire-forms/js/form.js'));
    File::delete(public_path('vendor/livewire-forms/js/grecaptcha.js'));
    File::delete(public_path('vendor/livewire-forms/js/livewire-forms.js'));
});
