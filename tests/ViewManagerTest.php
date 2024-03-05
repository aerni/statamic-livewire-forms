<?php

use Aerni\LivewireForms\Facades\ViewManager;

it('can get the path of a view', function () {
    config()->set('livewire-forms.view_path', 'my/custom/path');

    expect(ViewManager::viewPath('view'))->toBe('my/custom/path/view');
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

// it('can check if a theme exists', function () {
//     expect(ViewManager::themeExists('default'))->toBeTrue();
//     expect(ViewManager::themeExists('nope'))->toBeFalse();
// });
