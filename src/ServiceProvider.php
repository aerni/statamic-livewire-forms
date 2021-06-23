<?php

namespace Aerni\LivewireForms;

use Livewire\Livewire;
use Aerni\LivewireForms\Facades\Captcha;
use Illuminate\Support\Facades\Validator;
use Aerni\LivewireForms\Captcha\ReCaptcha;
use Aerni\LivewireForms\Http\Livewire\Form;
use Aerni\LivewireForms\Captcha\BaseCaptcha;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeLivewireForm::class,
    ];

    protected $tags = [
        Tags\Errors::class,
        Tags\Iterate::class,
        Tags\Captcha::class,
    ];

    public function boot()
    {
        parent::boot();

        Livewire::component('form', Form::class);

        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');

        $this->loadViewsFrom(__DIR__.'/../resources/views/antlers', 'livewire-forms');
        $this->loadViewsFrom(__DIR__.'/../resources/views/blade', 'livewire-forms');

        $this->publishes([
            __DIR__.'/../resources/views/antlers' => resource_path('views/vendor/livewire-forms'),
        ], 'livewire-forms-antlers');

        $this->publishes([
            __DIR__.'/../resources/views/blade' => resource_path('views/vendor/livewire-forms'),
        ], 'livewire-forms-blade');

        Validator::extend('captcha', function ($attribute, $value) {
            return Captcha::verify($value, $app['request']->getClientIp());
        });
    }

    public function register()
    {
        $this->app->bind(BaseCaptcha::class, function () {
            return new ReCaptcha();
        });
    }
}
