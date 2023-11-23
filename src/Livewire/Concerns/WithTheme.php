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
        // Try to get the theme defined in a custom component.
        if (isset(static::$THEME)) {
            return static::$THEME;
        }

        // Try to get the theme passed to the component in the view.
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
}
