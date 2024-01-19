<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Livewire\Attributes\Locked;

trait WithTheme
{
    #[Locked]
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
        if (is_dir(resource_path('views/'.config('livewire-forms.view_path')."/{$this->handle}"))) {
            return $this->handle;
        }

        // Fall back to the default theme.
        return config('livewire-forms.theme', 'default');
    }

    protected function evaluatedThemeView(string $view): string
    {
        $themeView = config('livewire-forms.view_path')."/{$this->theme}/{$view}";
        $defaultThemeView = config('livewire-forms.view_path').'/'.config('livewire-forms.theme', 'default')."/{$view}";

        return view()->exists($themeView) ? $themeView : $defaultThemeView;
    }
}
