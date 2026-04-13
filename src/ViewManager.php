<?php

namespace Aerni\LivewireForms;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Facades\Path;

class ViewManager
{
    public function absoluteViewPath(string $path = ''): string
    {
        return rtrim(Path::assemble(
            config('livewire.view_path', resource_path('views/livewire')),
            config('livewire-forms.view_path', 'forms'),
            $path,
        ), '/');
    }

    public function viewPath(string $view = ''): string
    {
        return Str::after($this->absoluteViewPath($view), resource_path('views/'));
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

        return is_dir($this->absoluteViewPath($theme));
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
        $path = $this->absoluteViewPath();

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
        $path = $this->absoluteViewPath();

        if (! File::isDirectory($path)) {
            return null;
        }

        return collect(File::directories($path))
            ->map(fn ($directory) => basename($directory))
            ->mapWithKeys(fn ($theme) => [$theme => str($theme)->replace(['_', '-'], ' ')->title()->toString()])
            ->all();
    }
}
