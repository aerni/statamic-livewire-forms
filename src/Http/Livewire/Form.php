<?php

namespace Aerni\LivewireForms\Http\Livewire;

use Aerni\LivewireForms\Facades\Models;
use Aerni\LivewireForms\Form\Component as FormComponent;
use Aerni\LivewireForms\Form\Fields;
use Aerni\LivewireForms\Form\Honeypot;
use Illuminate\Contracts\View\View as LaravelView;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Livewire\WithFileUploads;
use Statamic\Contracts\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Statamic\Events\SubmissionCreated;
use Statamic\Exceptions\SilentFormFailureException;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Statamic\Support\Str;

class Form extends Component
{
    use WithFileUploads;

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
            ->hydrateDefaultData();
    }

    public function hydrate(): void
    {
        $this->initializeComputedProperties();
    }

    protected function initializeProperties(): self
    {
        $this->handle = $this->handle ?? throw new \Exception('Please set the handle of the form you want to use.');
        $this->view = $this->view ?? $this->component->defaultView();
        $this->theme = $this->theme ?? $this->component->defaultTheme();

        return $this;
    }

    protected function initializeComputedProperties(): self
    {
        $this->component->view($this->view)->theme($this->theme);

        return $this;
    }

    protected function hydrateDefaultData(): self
    {
        /**
         * We only want to filter out any default value that is null.
         * We need to preserve empty arrays to make checkboxes work properly.
         */
        $this->data = $this->fields->defaultValues()->filter(fn ($value) => ! is_null($value))->toArray();

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
        return Fields::make($this->form, $this->id)
            ->models($this->models())
            ->data($this->data)
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

        try {
            $this->handleSpam()->handleSubmission()->handleSuccess();
        } catch (SilentFormFailureException) {
            $this->handleSuccess();
        }
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

    protected function handleSubmission(): self
    {
        return $this
            ->normalizeData()
            ->runSubmittingFormHook()
            ->makeSubmission()
            ->handleSubmissionEvents()
            ->storeSubmission()
            ->runSubmittedFormHook();
    }

    protected function handleSpam(): self
    {
        $isSpam = collect($this->data)->has($this->honeypot->handle);

        if ($isSpam) {
            throw new SilentFormFailureException();
        }

        return $this;
    }

    protected function normalizeData(): self
    {
        $this->data = collect($this->data)->map(function ($value, $key) {
            $field = $this->fields->get($key);

            // We want to return nothing if the field can't be found (e.g. honeypot).
            if (is_null($field)) {
                return null;
            }

            // We don't want to submit the captcha response value.
            if ($field->type === 'captcha') {
                return null;
            }

            if ($field->cast_booleans && in_array($value, ['true', 'false'])) {
                return Str::toBool($value);
            }

            if ($field->input_type === 'number') {
                return (int) $value;
            }

            return $value;
        })->filter()->toArray();

        return $this;
    }

    protected function runSubmittingFormHook(): self
    {
        $this->submittingForm();

        return $this;
    }

    protected function runSubmittedFormHook(): self
    {
        $this->submittedForm();

        return $this;
    }

    protected function submittingForm(): void
    {
        //
    }

    protected function submittedForm(): void
    {
        //
    }

    protected function makeSubmission(): self
    {
        $this->submission = $this->form->makeSubmission();

        $assetIds = $this->submission->uploadFiles($this->uploadedFiles());

        $values = collect($this->data)->merge($assetIds)->all();

        $processedValues = $this->form->blueprint()->fields()->addValues($values)->process()->values();

        $this->submission->data($processedValues);

        return $this;
    }

    protected function uploadedFiles(): array
    {
        return $this->fields->getByType('assets') // Only get asset fields.
            ->intersectByKeys($this->data) // Only get fields with data.
            ->map(function ($field, $handle) {
                return collect($this->data[$handle])
                    ->map(fn ($file) => new UploadedFile($file->getRealPath(), $file->getClientOriginalName(), $file->getMimeType()))
                    ->all();
            })->all();
    }

    protected function handleSubmissionEvents(): self
    {
        $this->emit('formSubmitted');
        $formSubmitted = FormSubmitted::dispatch($this->submission);

        if ($formSubmitted === false) {
            throw new SilentFormFailureException();
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

    protected function handleSuccess(): self
    {
        return $this->flashSuccess()->resetForm();
    }

    protected function resetForm(): self
    {
        // Merge the current data with the default values to preserve the captcha.
        $this->data = collect($this->data)
            ->merge($this->fields->defaultValues())
            ->filter(fn ($value) => ! is_null($value))
            ->toArray();

        // Make sure to process the fields using the newly reset data.
        $this->fields->data($this->data)->hydrate();

        // Reset asset fields using this trick: https://talltips.novate.co.uk/livewire/livewire-file-uploads-using-s3#removing-filename-from-input-field-after-upload
        $this->fields->getByType('assets')->each(function ($field) {
            $field->id($field->id().'_'.rand());
        });

        return $this;
    }

    protected function flashSuccess(): self
    {
        session()->flash('success', $this->successMessage());

        return $this;
    }

    public function successMessage(): string
    {
        return __('The form was successfully submitted!');
    }

    public function errorMessage(): string
    {
        return trans_choice('There was an error with your submission:|There were :count errors with your submission:', $this->getErrorBag()->count());
    }

    public function submitButtonLabel(): string
    {
        return __('Send');
    }
}
