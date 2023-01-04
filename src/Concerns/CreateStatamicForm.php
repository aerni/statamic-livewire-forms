<?php

namespace Aerni\LivewireForms\Concerns;

use Statamic\Contracts\Forms\Form as StatamicForm;
use Statamic\Facades\Form;
use Statamic\Support\Str;

trait CreateStatamicForm
{
    use AllowDynamicFormFields;

    public function findOrMakeForm(): StatamicForm
    {
        if ($form = Form::find($this->formHandle)) {
            return $form;
        }

        Form::make($this->formHandle)
            ->title(Str::slugToTitle($this->formHandle))
            ->save();

        return Form::find($this->formHandle);
    }
}
