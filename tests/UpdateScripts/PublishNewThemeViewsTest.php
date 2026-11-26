<?php

use Aerni\LivewireForms\Facades\ViewManager;
use Aerni\LivewireForms\UpdateScripts\PublishNewThemeViews;
use Illuminate\Support\Facades\File;

afterEach(function () {
    File::deleteDirectory(ViewManager::absoluteViewPath('legacy'));
});

it('publishes new theme views without replacing existing files', function () {
    $theme = ViewManager::absoluteViewPath('legacy');
    $defaultCheckboxes = ViewManager::absoluteViewPath('default/fields/checkboxes.blade.php');
    $publishedCheckboxes = File::get($defaultCheckboxes);

    File::ensureDirectoryExists($theme.'/fields');
    File::put($theme.'/fields/checkboxes.blade.php', 'custom checkboxes');

    (new PublishNewThemeViews('aerni/livewire-forms'))->update();

    File::put($theme.'/fields/short_answer.blade.php', 'custom short answer');

    (new PublishNewThemeViews('aerni/livewire-forms'))->update();

    $package = dirname(__DIR__, 2).'/resources/views/default';

    expect(File::get($theme.'/fields/checkboxes.blade.php'))->toBe('custom checkboxes')
        ->and(File::get($theme.'/fields/short_answer.blade.php'))->toBe('custom short answer')
        ->and(File::get($theme.'/fields/upload.blade.php'))->toBe(File::get($package.'/fields/upload.blade.php'))
        ->and(File::get($theme.'/fields/group.blade.php'))->toBe(File::get($package.'/fields/group.blade.php'))
        ->and(File::get($defaultCheckboxes))->toBe($publishedCheckboxes);
});

it('registers the theme view update script', function () {
    expect(collect(app('statamic.update-scripts'))->pluck('class'))
        ->toContain(PublishNewThemeViews::class);
});
