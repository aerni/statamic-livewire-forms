<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait WithTheme
{
    public string $theme;

    public function mountWithTheme(): void
    {
        $this->theme = $this->theme();
    }

    protected function theme(): string
    {
        // Get the theme passed to the component in the view.
        if (isset($this->theme)) {
            return $this->theme;
        }

        // Autoload the theme by form handle if it exists.
        if (is_dir(resource_path("views/vendor/livewire-forms/{$this->handle}"))) {
            return $this->handle;
        }

        // Fall back to the default theme.
        return config('livewire-forms.theme', 'default');
    }

    protected function evaluatedThemeView(string $view): string
    {
        $defaultTheme = config('livewire-forms.theme', 'default');
        $themeView = "livewire-forms::{$this->theme}.{$view}";
        $defaultThemeView = "livewire-forms::{$defaultTheme}.{$view}";

        return view()->exists($themeView) ? $themeView : $defaultThemeView;
    }
}
