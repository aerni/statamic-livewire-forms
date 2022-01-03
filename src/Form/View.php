<?php

namespace Aerni\LivewireForms\Form;

class View
{
    const DEFAULT_THEME = 'livewire-forms';

    protected string $theme;
    protected string $view;

    public function theme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function get(string $view): string
    {
        $this->view = $view;

        return $this->isDefaultTheme()
            ? $this->getDefaultThemeView()
            : $this->getCustomThemeView();
    }

    protected function getDefaultThemeView(): string
    {
        return self::DEFAULT_THEME . '::' . $this->view;
    }

    protected function getCustomThemeView(): string
    {
        $themeView = "livewire.forms.{$this->theme}.{$this->view}";
        $fallback = $this->getDefaultThemeView($this->view);

        return view()->exists($themeView) ? $themeView : $fallback;
    }

    protected function isDefaultTheme(): bool
    {
        return $this->theme === self::DEFAULT_THEME;
    }
}
