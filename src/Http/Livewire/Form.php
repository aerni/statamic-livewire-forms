<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Livewire\Component;
use Statamic\Facades\Site;
use Illuminate\Support\Arr;
use Statamic\Forms\SendEmails;
use Aerni\LivewireForms\Form\Component as FormComponent;
use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\URL;
use Aerni\LivewireForms\Form\Fields;
use Aerni\LivewireForms\Form\Honeypot;
use Statamic\Events\SubmissionCreated;
use Aerni\LivewireForms\Facades\Models;
use Illuminate\Contracts\View\View as LaravelView;
use Statamic\Contracts\Forms\Submission;
use Statamic\Exceptions\SilentFormFailureException;

class Form extends Component
{
    protected array $models = [];
    protected Submission $submission;

    public string $handle;
    public string $view;
    public string $theme;
    public array $data = [];

    public function mount(): void
    {
        $this
            ->initializeProperties()
            ->initializeComputedProperties()
            ->hydrateData();
    }

    public function hydrate(): void
    {
        $this->initializeComputedProperties();
    }

    protected function initializeProperties(): self
    {
        $this->handle = $this->handle ?? throw new \Exception('Please set the handle of the form you want to use.');
        $this->theme = $this->theme ?? $this->component->defaultTheme();
        $this->view = $this->view ?? $this->component->defaultView();

        return $this;
    }

    protected function initializeComputedProperties(): self
    {
        $this->component->view($this->view)->theme($this->theme);

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

    public function getComponentProperty(): FormComponent
    {
        return \Aerni\LivewireForms\Facades\Component::getFacadeRoot();
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
        $this->handleSubmission();
    }

    public function render(): LaravelView
    {
        return view("livewire.forms.{$this->view}");
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

    protected function handleSubmission(): void
    {
        try {
            $this
                ->handleSpam()
                ->prepareSubmissionData()
                ->runSubmittingCallback()
                ->makeSubmission()
                ->handleSubmissionEvents()
                ->storeSubmission()
                ->success();
        } catch (SilentFormFailureException $e) {
            $this->success();
        }
    }

    protected function handleSpam(): self
    {
        $isSpam = (bool) Arr::get($this->data, $this->honeypot->handle);

        if ($isSpam) {
            throw new SilentFormFailureException;
        }

        return $this;
    }

    protected function prepareSubmissionData(): self
    {
        $this->data = $this->fields->normalizeData($this->data);

        return $this;
    }

    protected function runSubmittingCallback(): self
    {
        $this->submitting();

        return $this;
    }

    protected function submitting(): void
    {
        //
    }

    protected function makeSubmission(): self
    {
        $this->submission = $this->form->makeSubmission()->data($this->data);

        return $this;
    }

    protected function handleSubmissionEvents(): self
    {
        $this->emit('formSubmitted');
        $formSubmitted = FormSubmitted::dispatch($this->submission);

        if ($formSubmitted === false) {
            throw new SilentFormFailureException;
        }

        $this->emit('submissionCreated');
        SubmissionCreated::dispatch($this->submission);

        $site = Site::findByUrl(URL::previous());
        SendEmails::dispatch($this->submission, $site);

        return $this;
    }

    protected function storeSubmission(): self
    {
        if ($this->form->store()) {
            $this->submission->save();
        }

        return $this;
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
