<?php

namespace Aerni\StatamicLivewireForms;

use Aerni\StatamicLivewireForms\Http\Livewire\StatamicForm;
use Livewire\Livewire;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeStatamicLivewireForm::class,
    ];

    protected $tags = [
        Tags\Errors::class,
    ];

    public function boot()
    {
        parent::boot();

        Livewire::component('form', StatamicForm::class);

        $this->loadViewsFrom(__DIR__.'/../resources/views/antlers', 'statamic-livewire-forms');
        $this->loadViewsFrom(__DIR__.'/../resources/views/blade', 'statamic-livewire-forms');

        $this->publishes([
            __DIR__.'/../resources/views/antlers' => resource_path('views/vendor/statamic-livewire-forms'),
        ], 'statamic-livewire-forms-antlers');

        $this->publishes([
            __DIR__.'/../resources/views/blade' => resource_path('views/vendor/statamic-livewire-forms'),
        ], 'statamic-livewire-forms-blade');
    }
}
