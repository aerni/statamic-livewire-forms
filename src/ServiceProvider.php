<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Aerni\LivewireForms\Http\Livewire\DefaultForm;
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

    public function bootAddon()
    {
        $this
            ->registerTranslations()
            ->registerPublishables()
            ->registerBladeDirectives()
            ->registerValidators()
            ->registerLivewireComponents();
    }

    protected function registerTranslations(): self
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'livewire-forms');

        return $this;
    }

    protected function registerPublishables(): self
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-forms'),
        ], 'livewire-forms-views');

        return $this;
    }

    protected function registerBladeDirectives(): self
    {
        foreach (get_class_methods(BladeDirectives::class) as $method) {
            Blade::directive($method, [BladeDirectives::class, $method]);
        }

        return $this;
    }

    protected function registerValidators(): self
    {
        Validator::extend('captcha', function ($attribute, $value) {
            return Captcha::verifyResponse($value, request()->getClientIp());
        }, __('livewire-forms::messages.captcha_challenge'));

        return $this;
    }

    protected function registerLivewireComponents(): self
    {
        Livewire::component('form', Form::class);
        Livewire::component('default-form', DefaultForm::class);

        return $this;
    }
}
