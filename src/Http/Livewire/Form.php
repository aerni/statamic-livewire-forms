<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Livewire\Component;
use Statamic\Support\Str;
use Statamic\Facades\Site;
use Illuminate\Support\Arr;
use Statamic\Forms\SendEmails;
use Statamic\Events\FormSubmitted;
use Illuminate\Contracts\View\View as LaravelView;
use Illuminate\Support\Facades\URL;
use Aerni\LivewireForms\Form\Fields;
use Aerni\LivewireForms\Form\Honeypot;
use Statamic\Events\SubmissionCreated;
use Aerni\LivewireForms\Facades\Models;
use Aerni\LivewireForms\Form\View;
use Statamic\Exceptions\SilentFormFailureException;

class Form extends Component
{
    protected array $models = [];

    public string $handle;
    public string $component;
    public string $theme = 'livewire-forms';
    public array $data = [];

    public function mount(): void
    {
        $this->initializeProperties();
    }

    public function booted(): void
    {
        $this->hydrateData();
    }

    protected function initializeProperties(): self
    {
        $this->handle = $this->handle ?? throw new \Exception('Please set the handle of the form you want to use.');
        $this->component = $this->component ?? Str::slug($this->handle);

        return $this;
    }

    protected function hydrateData(): self
    {
        $this->data = $this->fields->defaultValues();

        return $this;
    }

    protected function hydratedFields(Fields $fields): void
    {
        //
    }

    protected function models(): array
    {
        return Models::all()->merge($this->models)->toArray();
    }

    public function getViewProperty(): View
    {
        return View::make($this->theme);
    }

    public function getFormProperty(): \Statamic\Forms\Form
    {
        return \Statamic\Facades\Form::find($this->handle)
            ?? throw new \Exception("Form with handle [{$this->handle}] cannot be found.");
    }

    public function getFieldsProperty(): Fields
    {
        return Fields::make($this->form, $this->id, $this->data)
            ->models($this->models())
            ->hydrated(fn ($fields) => $this->hydratedFields($fields))
            ->hydrate();
    }

    public function getHoneypotProperty(): Honeypot
    {
        return Honeypot::make($this->form->honeypot(), $this->id);
    }

    public function submit(): void
    {
        $this->validate();
        $this->handleFormSubmission();
    }

    public function render(): LaravelView
    {
        return view("livewire.forms.{$this->component}");
    }

    protected function updated(string $field): void
    {
        $this->validateOnly($field, $this->realtimeRules($field));
    }

    protected function rules(): array
    {
        return $this->fields->validationRules();
    }

    protected function realtimeRules(string $field): array
    {
        return $this->fields->realtimeValidationRules($field);
    }

    protected function validationAttributes(): array
    {
        return $this->fields->validationAttributes();
    }

    protected function handleFormSubmission(): void
    {
        try {
            if ($this->isSpam()) {
                throw new SilentFormFailureException;
            }

            $data = $this->normalizeDataForSubmission($this->data);
            $submission = $this->form->makeSubmission()->data($data);

            $this->emit('formSubmitted');

            if (FormSubmitted::dispatch($submission) === false) {
                throw new SilentFormFailureException;
            }
        } catch (SilentFormFailureException $e) {
            $this->success();
            return;
        }

        if ($this->form->store()) {
            $submission->save();
        }

        $this->emit('submissionCreated');
        SubmissionCreated::dispatch($submission);

        $site = Site::findByUrl(URL::previous());
        SendEmails::dispatch($submission, $site);

        $this->success();
    }

    protected function normalizeDataForSubmission(array $data): array
    {
        return collect($data)->map(function ($value, $key) {
            $field = $this->fields->get($key);

            // We want to return nothing if the field can't be found (e.g. honeypot).
            if (is_null($field)) {
                return null;
            }

            // We don't want to submit the captcha response value.
            if ($field->type === 'captcha') {
                return null;
            }

            if ($field->cast_booleans) {
                return Str::toBool($value);
            }

            if ($field->input_type === 'number') {
                return (int) $value;
            }

            return $value;
        })->filter()->toArray();
    }

    protected function isSpam(): bool
    {
        return (bool) Arr::get($this->data, $this->honeypot->handle);
    }

    protected function success(): void
    {
        /**
         * Merge the current data with the default values instead of resetting it.
         * This way we can preserve the captcha and honeypot values.
         */
        $this->data = array_merge($this->data, $this->fields->defaultValues());

        // Process the field conditions using the newly reset data.
        $this->fields->data($this->data)->processFieldConditions();

        session()->flash('success');
    }
}
