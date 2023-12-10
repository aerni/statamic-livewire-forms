<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

trait WithStatamicFormBuilder
{
    use WithHandle;
    use WithTheme;
    use WithView;
    use WithData;
    use WithFields;
    use WithForm;
    use WithMessages;
    use RendersView;
    use SubmitsForm;
}
