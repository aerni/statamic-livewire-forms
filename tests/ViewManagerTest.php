<?php

use Aerni\LivewireForms\Facades\ViewManager;

it('resolves view path relative to Livewire view path', function () {
    expect(ViewManager::viewPath('view'))->toBe('livewire/forms/view');
});

it('resolves view path with custom Livewire view path', function () {
    config()->set('livewire.view_path', resource_path('views/wire'));

    expect(ViewManager::viewPath('view'))->toBe('wire/forms/view');
});

it('resolves view path with custom forms view path', function () {
    config()->set('livewire-forms.view_path', 'custom');

    expect(ViewManager::viewPath('view'))->toBe('livewire/custom/view');
});

it('resolves view path without a view', function () {
    expect(ViewManager::viewPath())->toBe('livewire/forms');
});

it('resolves absolute view path', function () {
    expect(ViewManager::absoluteViewPath())
        ->toBe(resource_path('views/livewire/forms'));
});

it('resolves absolute view path with a subpath', function () {
    expect(ViewManager::absoluteViewPath('default'))
        ->toBe(resource_path('views/livewire/forms/default'));
});

it('resolves absolute view path with custom Livewire view path', function () {
    config()->set('livewire.view_path', resource_path('views/wire'));

    expect(ViewManager::absoluteViewPath('default'))
        ->toBe(resource_path('views/wire/forms/default'));
});

it('can get the default view', function () {
    config()->set('livewire-forms.view', 'custom');

    expect(ViewManager::defaultView())->toBe('custom');
});

it('can get the default theme', function () {
    config()->set('livewire-forms.theme', 'custom');

    expect(ViewManager::defaultTheme())->toBe('custom');
});

it('can check if a view exists', function () {
    expect(ViewManager::viewExists('default'))->toBeTrue();
    expect(ViewManager::viewExists('nope'))->toBeFalse();
});

it('can check if a theme view exists', function () {
    expect(ViewManager::themeViewExists('default', 'fields.default'))->toBeTrue();
    expect(ViewManager::themeViewExists('default', 'fields.nope'))->toBeFalse();
});
