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

    public function boot()
    {
        parent::boot();

        Livewire::component('statamic-form', StatamicForm::class);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/statamic-livewire-forms'),
        ], 'statamic-livewire-forms-views');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/statamic-livewire-forms'),
        ], 'statamic-livewire-forms-lang');
    }
}
