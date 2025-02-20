<?php

namespace Aerni\LivewireForms\UpdateScripts;

use Aerni\LivewireForms\Facades\ViewManager;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class AddDictionaryFieldView extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('9.6.0');
    }

    public function update()
    {
        collect(ViewManager::themes())
            ->each(function ($name, $handle) {
                $view = ViewManager::viewPath("{$handle}/fields/dictionary.blade.php");
                File::copy(__DIR__.'/../../resources/views/default/fields/dictionary.blade.php', resource_path("views/{$view}"));
            });

        $this->console()->info('Added dictionary field view.');
    }
}
