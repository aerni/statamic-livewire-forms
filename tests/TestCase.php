<?php

namespace Aerni\LivewireForms\Tests;

use Statamic\Statamic;
use Statamic\Extend\Manifest;
use Livewire\LivewireServiceProvider;
use Aerni\LivewireForms\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
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
                'autoload' => 'src'
            ],
        ];

        tap($app['config'], function (Repository $config) {
            $config->set('livewire-forms', require (__DIR__."/../config/livewire-forms.php"));
            $config->set('livewire-forms.view_path', '/');

            $config->set('view.paths', [__DIR__."/../resources/views"]);
        });
    }
}
