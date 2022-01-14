<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Aerni\LivewireForms\Http\Livewire\Form;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeComponent::class,
        Commands\MakeTheme::class,
        Commands\MakeView::class,
        Commands\Setup::class,
    ];

    protected $fieldtypes = [
        Fieldtypes\Captcha::class,
    ];

    public function boot()
    {
        parent::boot();

        $this->registerTranslations();
        $this->registerPublishables();
        $this->registerBladeDirectives();
        $this->registerValidators();
        $this->registerLivewireComponents();
    }

    protected function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'livewire-forms');
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
    }

    protected function registerPublishables()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-forms'),
        ], 'livewire-forms-views');
    }

    protected function registerBladeDirectives()
    {
        foreach (get_class_methods(BladeDirectives::class) as $method) {
            Blade::directive($method, [BladeDirectives::class, $method]);
        }
    }

    protected function registerValidators()
    {
        Validator::extend('captcha', function ($attribute, $value) {
            return Captcha::verifyResponse($value, request()->getClientIp());
        }, __('livewire-forms::validation.captcha_challenge'));
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('form', Form::class);
    }
}
