<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Aerni\LivewireForms\Livewire\DynamicForm;
use Aerni\LivewireForms\Livewire\Form;
use Aerni\LivewireForms\Livewire\Synthesizers\FieldSynth;
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
            ->registerBladeDirectives()
            ->registerValidators()
            ->registerLivewire()
            ->registerSelectableFieldtypes();
    }

    protected function registerTranslations(): self
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'livewire-forms');

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

    protected function registerLivewire(): self
    {
        Livewire::component('form', DynamicForm::class);
        Livewire::component('default-form', Form::class);
        Livewire::propertySynthesizer(FieldSynth::class);

        return $this;
    }

    protected function registerSelectableFieldtypes(): self
    {
        \Statamic\Fieldtypes\Hidden::makeSelectableInForms();

        return $this;
    }
}
