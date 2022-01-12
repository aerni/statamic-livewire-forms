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
        if (! is_dir(resource_path("views/livewire/forms/{$theme}"))) {
            throw new \Exception("Theme [{$theme}] not found.");
        }

        $this->theme = $theme;

        return $this;
    }

    public function getView(string $view): string
    {
        $themeView = "livewire.forms.{$this->theme}.{$view}";
        $defaultView = "livewire.forms.{$this->defaultTheme()}.{$view}";
        $fallback = "livewire-forms::{$view}";

        if (view()->exists($themeView)) {
            return $themeView;
        }

        if (view()->exists($defaultView)) {
            return $defaultView;
        }

        return $fallback;
    }
}
