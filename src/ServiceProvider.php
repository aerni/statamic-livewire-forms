<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Aerni\LivewireForms\Livewire\DynamicForm;
use Aerni\LivewireForms\Livewire\Form;
use Aerni\LivewireForms\Livewire\Synthesizers\FieldSynth;
use Aerni\LivewireForms\Livewire\Synthesizers\RuleSynth;
use Aerni\LivewireForms\Livewire\WizardForm;
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

    protected $scripts = [
        __DIR__.'/../resources/dist/js/livewire-forms.js',
    ];

    public function bootAddon()
    {
        $this
            ->bootBladeDirectives()
            ->bootValidators()
            ->bootLivewire()
            ->bootSelectableFieldtypes();
    }

    protected function bootBladeDirectives(): self
    {
        foreach (get_class_methods(BladeDirectives::class) as $method) {
            Blade::directive($method, [BladeDirectives::class, $method]);
        }

        return $this;
    }

    protected function bootValidators(): self
    {
        Validator::extend('captcha', function ($attribute, $value) {
            return Captcha::verifyResponse($value, request()->getClientIp());
        }, __('livewire-forms::messages.captcha_challenge'));

        return $this;
    }

    protected function bootLivewire(): self
    {
        Livewire::component('form', DynamicForm::class);
        Livewire::component('default-form', Form::class);
        Livewire::component('wizard-form', WizardForm::class);
        Livewire::propertySynthesizer(FieldSynth::class);
        Livewire::propertySynthesizer(RuleSynth::class);

        return $this;
    }

    protected function bootSelectableFieldtypes(): self
    {
        \Statamic\Fieldtypes\Hidden::makeSelectableInForms();

        return $this;
    }
}
