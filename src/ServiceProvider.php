<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Facades\Captcha;
use Aerni\LivewireForms\Livewire\BaseForm;
use Aerni\LivewireForms\Livewire\DynamicForm;
use Aerni\LivewireForms\Livewire\Synthesizers\FieldSynth;
use Aerni\LivewireForms\Livewire\Synthesizers\RuleSynth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Statamic\Facades\Form;
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
            ->bootSelectableFieldtypes()
            ->bootFormConfigFields();
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
        Livewire::component('base-form', BaseForm::class);

        Livewire::propertySynthesizer(FieldSynth::class);
        Livewire::propertySynthesizer(RuleSynth::class);

        return $this;
    }

    protected function bootSelectableFieldtypes(): self
    {
        \Statamic\Fieldtypes\Hidden::makeSelectableInForms();

        return $this;
    }

    protected function bootFormConfigFields(): self
    {
        Form::appendConfigFields('*', __('Livewire Forms'), [
            'type' => [
                'type' => 'button_group',
                'display' => __('Type'),
                'instructions' => __('Choose the desired type for this form.'),
                'options' => [
                    'basic' => __('Basic'),
                    'wizard' => __('Wizard'),
                ],
                'default' => 'basic',
            ],
            'view' => [
                'type' => 'select',
                'display' => __('View'),
                'instructions' => __('Choose the view for this form.'),
                'options' => $this->viewOptions(),
                'clearable' => true,
                'width' => 50,
            ],
            'theme' => [
                'type' => 'select',
                'display' => __('Theme'),
                'instructions' => __('Choose the theme for this form.'),
                'options' => $this->themeOptions(),
                'clearable' => true,
                'width' => 50,
            ],
            'redirect' => [
                'type' => 'link',
                'display' => __('Redirect URL'),
                'instructions' => __('The users will be redirected to this URL after the form was submitted.'),
            ],
        ]);

        return $this;
    }

    protected function viewOptions(): ?array
    {
        $path = resource_path('views/'.config('livewire-forms.view_path'));

        if (! File::isDirectory($path)) {
            return null;
        }

        return collect(File::files($path))
            ->map(fn ($file) => Str::before($file->getBasename(), '.'))
            ->mapWithKeys(fn ($view) => [$view => str($view)->replace(['_', '-'], ' ')->title()->toString()])
            ->all();
    }

    protected function themeOptions(): ?array
    {
        $path = resource_path('views/'.config('livewire-forms.view_path'));

        if (! File::isDirectory($path)) {
            return null;
        }

        return collect(File::directories($path))
            ->map(fn ($directory) => basename($directory))
            ->mapWithKeys(fn ($theme) => [$theme => str($theme)->replace(['_', '-'], ' ')->title()->toString()])
            ->all();
    }
}
