<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Http\Livewire\Form;
use Livewire\Livewire;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeLivewireForm::class,
    ];

    protected $tags = [
        Tags\Errors::class,
        Tags\Iterate::class
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
    }
}
