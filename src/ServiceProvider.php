<?php

namespace Aerni\LivewireForms;

use Aerni\LivewireForms\Commands\MakeViewCommand;
use Aerni\LivewireForms\Http\Livewire\LivewireForm;
use Livewire\Livewire;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        parent::boot();
        Livewire::component('livewire-form', LivewireForm::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeViewCommand::class,
            ]);
        }
    }
}
