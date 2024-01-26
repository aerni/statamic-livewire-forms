<?php

namespace Aerni\LivewireForms\Tests;

use Statamic\Facades\File;
use Statamic\Extend\Manifest;
use Livewire\LivewireServiceProvider;
use Aerni\LivewireForms\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Aerni\LivewireForms\Facades\ViewManager;
use Orchestra\Testbench\TestCase as Orchestra;
use Statamic\Providers\StatamicServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            LivewireServiceProvider::class,
            StatamicServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app->make(Manifest::class)->manifest = [
            'aerni/livewire-forms' => [
                'id' => 'aerni/livewire-forms',
                'namespace' => 'Aerni\\LivewireForms',
                'provider' => 'Aerni\\LivewireForms\\ServiceProvider',
                'autoload' => 'src',
            ],
        ];

        tap($app['config'], function (Repository $config) {
            $config->set('livewire-forms', require (__DIR__.'/../config/livewire-forms.php'));
        });

        $this->copyResources();
    }

    protected function copyResources(): void
    {
        if (! ViewManager::themeExists('default')) {
            File::copyDirectory(__DIR__.'/../resources/views/default/', resource_path('views'.'/'.config('livewire-forms.view_path').'/default'));
        }

        if (! ViewManager::viewExists('default')) {
            File::copy(__DIR__.'/../resources/views/default.blade.php', resource_path('views'.'/'.config('livewire-forms.view_path').'/default.blade.php'));
        }
    }
}
