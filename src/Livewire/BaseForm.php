<?php

namespace Aerni\LivewireForms\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Aerni\LivewireForms\Fields\Assets;
use Aerni\LivewireForms\Fields\Captcha;
use Aerni\LivewireForms\Facades\ViewManager;
use Aerni\LivewireForms\Livewire\Concerns\WithForm;
use Aerni\LivewireForms\Livewire\Concerns\WithType;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Aerni\LivewireForms\Livewire\Concerns\WithSteps;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithFields;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\SubmitsForm;
use Aerni\LivewireForms\Livewire\Concerns\WithMessages;
use Aerni\LivewireForms\Livewire\Concerns\WithRedirect;
use Aerni\LivewireForms\Livewire\Concerns\WithSections;

class BaseForm extends Component
{
    use WithHandle;
    use WithTheme;
    use WithType;
    use WithView;
    use WithForm;
    use WithFields;
    use WithSections;
    use WithSteps;
    use WithMessages;
    use WithRedirect;
    use SubmitsForm;

    public function render(): View
    {
        return view(ViewManager::viewPath($this->view), [
            'step' => $this->currentStep(),
        ]);
    }

    #[Computed]
    public function assets(): string
    {
        $styles = collect();
        $scripts = collect(['/vendor/livewire-forms/js/form.js']);

        if ($this->fields->contains(fn ($field) => $field instanceof Assets)) {
            $styles->push('/vendor/livewire-forms/css/filepond.css');
            $scripts->push('/vendor/livewire-forms/js/filepond.js');
        }

        if ($this->fields->contains(fn ($field) => $field instanceof Captcha)) {
            $scripts->push('/vendor/livewire-forms/js/grecaptcha.js');
        }

        $styles = $styles->map(fn ($style) => "<link href='{$style}' rel='stylesheet'/>");
        $scripts = $scripts->map(fn ($script) => "<script src='{$script}' type='module'></script>");

        return $styles->merge($scripts)->implode("\n");
    }
}
