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
    }
}
