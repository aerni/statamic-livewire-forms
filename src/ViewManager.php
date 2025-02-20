<?php

namespace Aerni\LivewireForms;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ViewManager
{
    public function viewPath(string $view): string
    {
        return config('livewire-forms.view_path', 'livewire/forms')."/{$view}";
    }

    public function themeViewPath(string $theme, string $view): string
    {
        $themeView = $this->viewPath("{$theme}/{$view}");
        $defaultThemeView = $this->viewPath("{$this->defaultTheme()}/{$view}");

        return view()->exists($themeView) ? $themeView : $defaultThemeView;
    }

    public function themeViewExists(string $theme, string $view): bool
    {
        return view()->exists($this->themeViewPath($theme, $view));
    }

    public function viewExists(?string $view): bool
    {
        if (empty($view)) {
            return false;
        }

        return view()->exists($this->viewPath($view));
    }

    public function themeExists(?string $theme): bool
    {
        if (empty($theme)) {
            return false;
        }

        return is_dir(resource_path("views/{$this->viewPath($theme)}"));
    }

    public function defaultView(): string
    {
        return config('livewire-forms.view', 'default');
    }

    public function defaultTheme(): string
    {
        return config('livewire-forms.theme', 'default');
    }

    public function views(): ?array
    {
        $path = resource_path('views/'.config('livewire-forms.view_path'));

        if (! File::isDirectory($path)) {
            return null;
        }

        return collect(File::files($path))
            ->map(fn ($file) => Str::before($file->getBasename(), '.'))
            ->mapWithKeys(fn ($view) => [$view => str($view)->replace(['_', '-'], ' ')->title()->toString()])
            ->all();
    }

    public function themes(): ?array
    {
        $path = resource_path('views/'.config('livewire-forms.view_path'));

        if (! File::isDirectory($path)) {
            return null;
        }

        return collect(File::directories($path))
            ->map(fn ($directory) => basename($directory))
            ->mapWithKeys(fn ($theme) => [$theme => str($theme)->replace(['_', '-'], ' ')->title()->toString()])
            ->all();
    }
}
