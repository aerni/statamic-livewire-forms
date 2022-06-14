<?php

namespace Aerni\LivewireForms\Form;

use Illuminate\Support\Str;
use Livewire\LivewireComponentsFinder;

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

        return view()->exists($themeView) ? $themeView : $defaultView;
    }

    public function getComponent(string $handle): string
    {
        $key = Str::replace('_', '-', $handle).'-form';
        $component = app(LivewireComponentsFinder::class)->build()->find($key);

        return $component ? $key : 'default-form';
    }
}
