<?php

namespace Aerni\LivewireForms;

use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;
use Aerni\LivewireForms\BladeDirectives;
use Aerni\LivewireForms\Facades\Captcha;
use Illuminate\Support\Facades\Validator;
use Aerni\LivewireForms\Http\Livewire\Form;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeLivewireForm::class,
    ];

    protected $fieldtypes = [
        Fieldtypes\Captcha::class,
    ];

    protected $tags = [
        Tags\LivewireForms::class,
        Tags\Iterate::class,
    ];

    public function boot()
    {
        parent::boot();

        $this->registerViews();
        $this->registerTranslations();
        $this->registerPublishables();
        $this->registerBladeDirectives();
        $this->registerValidators();
        $this->registerLivewireComponents();
    }

    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/antlers', 'livewire-forms');
        $this->loadViewsFrom(__DIR__.'/../resources/views/blade', 'livewire-forms');
        $this->loadViewsFrom(__DIR__.'/../resources/views/captcha', 'livewire-forms');
    }

    protected function registerTranslations()
    {
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
    }

    protected function registerPublishables()
    {
        $this->publishes([
            __DIR__.'/../resources/views/antlers' => resource_path('views/vendor/livewire-forms'),
        ], 'livewire-forms-antlers');

        $this->publishes([
            __DIR__.'/../resources/views/blade' => resource_path('views/vendor/livewire-forms'),
        ], 'livewire-forms-blade');
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('captchaKey', [BladeDirectives::class, 'captchaKey']);
    }

    protected function registerValidators()
    {
        Validator::extend('captcha', function ($attribute, $value) {
            return Captcha::verifyResponse($value, request()->getClientIp());
        }, __('The reCAPTCHA challenge was not successful.'));

        // TODO: Get translation from a php file.
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('form', Form::class);
    }
}
