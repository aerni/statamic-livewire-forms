<?php

namespace Aerni\LivewireForms\UpdateScripts;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\UpdateScripts\UpdateScript;

class MigrateViewPath extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('10.3.0');
    }

    public function update()
    {
        $configPath = config_path('livewire-forms.php');

        if (! File::exists($configPath)) {
            return;
        }

        $oldViewPath = config('livewire-forms.view_path');
        $oldAbsolutePath = resource_path("views/{$oldViewPath}");
        $livewireViewPath = config('livewire.view_path', resource_path('views/livewire'));

        if (! str_starts_with($oldAbsolutePath, "{$livewireViewPath}/")) {
            $this->console()->warn("The 'view_path' config is now relative to Livewire's configured view path. Your custom value '{$oldViewPath}' could not be auto-migrated. Please update config/livewire-forms.php manually.");

            return;
        }

        $newViewPath = Str::after($oldAbsolutePath, "{$livewireViewPath}/");

        if ($newViewPath === $oldViewPath) {
            return;
        }

        $contents = File::get($configPath);

        $contents = str_replace(
            "'view_path' => '{$oldViewPath}'",
            "'view_path' => '{$newViewPath}'",
            $contents,
        );

        File::put($configPath, $contents);

        $this->console()->info("Updated 'view_path' config from '{$oldViewPath}' to '{$newViewPath}'. The path is now relative to Livewire's configured view path.");
    }
}
