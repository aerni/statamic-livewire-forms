<?php

namespace Aerni\LivewireForms\UpdateScripts;

use Aerni\LivewireForms\Facades\ViewManager;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class PublishNewThemeViews extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('11.0.0');
    }

    public function update()
    {
        $themes = ViewManager::themes();

        if (! $themes) {
            return;
        }

        collect(array_keys($themes))
            ->each(fn (string $theme) => $this->publishNewViews($theme));

        $this->console()->info('Published new Livewire Forms views.');
    }

    protected function publishNewViews(string $theme): void
    {
        $source = __DIR__.'/../../resources/views/default';
        $destination = ViewManager::absoluteViewPath($theme);

        foreach (File::allFiles($source) as $file) {
            $target = $destination.'/'.$file->getRelativePathname();

            if (File::exists($target)) {
                continue;
            }

            File::ensureDirectoryExists(dirname($target));
            File::copy($file->getPathname(), $target);
        }
    }
}
