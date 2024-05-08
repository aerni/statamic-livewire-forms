<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Facades\ViewManager;
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
        /* Get the theme passed to the component in the view. */
        if (isset($this->theme)) {
            return $this->theme;
        }

        /* Autoload the theme by form handle if it exists. */
        if (ViewManager::themeExists($this->handle)) {
            return $this->handle;
        }

        /* Fall back to the default theme. */
        return ViewManager::defaultTheme();
    }
}
