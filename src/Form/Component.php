<?php

namespace Aerni\LivewireForms\Form;

class Component
{
    protected string $theme;

    protected string $view;

    public function defaultView(): string
    {
        return config('livewire-forms.view', 'default');
    }

    public function defaultTheme(): string
    {
        return config('livewire-forms.theme', 'default');
    }

    public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function theme(string $theme): self
    {
        if (! is_dir(resource_path("views/vendor/livewire-forms/themes/{$theme}"))) {
            throw new \Exception("The theme [{$theme}] doesn't exist. Please create it or use a different theme.");
        }

        $this->theme = $theme;

        return $this;
    }

    public function getView(string $view): string
    {
        $themeView = "livewire-forms::themes.{$this->theme}.{$view}";
        $defaultView = "livewire-forms::themes.{$this->defaultTheme()}.{$view}";

        return view()->exists($themeView) ? $themeView : $defaultView;
    }
}
