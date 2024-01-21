<?php

namespace Aerni\LivewireForms;

use Livewire\Component;

class ViewManager
{
    protected Component $component;

    public function boot(Component $component): void
    {
        $this->component = $component;
    }

    public function viewPath(string $view): string
    {
        return config('livewire-forms.view_path', 'livewire/forms')."/{$view}";
    }

    public function themeViewPath(string $view): string
    {
        $themeView = $this->viewPath("{$this->component->theme}/{$view}");
        $defaultThemeView = $this->viewPath("{$this->defaultTheme()}/{$view}");

        return view()->exists($themeView) ? $themeView : $defaultThemeView;
    }

    public function viewExists(string $view): bool
    {
        return view()->exists($this->viewPath($view));
    }

    public function themeExists(string $theme): bool
    {
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
}
