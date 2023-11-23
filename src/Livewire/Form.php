<?php

namespace Aerni\LivewireForms\Livewire;

use Livewire\Component;
use Statamic\Support\Str;
use Statamic\Facades\Site;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Statamic\Forms\SendEmails;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\URL;
use Aerni\LivewireForms\Form\Fields;
use Illuminate\Support\Facades\Lang;
use Statamic\Events\SubmissionCreated;
use Aerni\LivewireForms\Fields\Honeypot;
use Statamic\Contracts\Forms\Submission;
use Illuminate\Contracts\View\View as LaravelView;
use Aerni\LivewireForms\Livewire\Concerns\WithData;
use Aerni\LivewireForms\Livewire\Concerns\WithView;
use Statamic\Exceptions\SilentFormFailureException;
use Aerni\LivewireForms\Livewire\Concerns\WithTheme;
use Aerni\LivewireForms\Livewire\Concerns\WithHandle;
use Aerni\LivewireForms\Livewire\Concerns\WithModels;

class Form extends Component
{
    use WithHandle;
    use WithView;
    use WithTheme;
    use WithData;
    use WithModels;
    use WithFileUploads;

    protected Submission $submission;

    public Collection $fieldsToSubmit;

    public function mount(): void
    {
        $this->fieldsToSubmit = collect();
    }

    protected function hydratedFields(Fields $fields): void
    {
        //
    }

    #[Computed(true)]
    public function form(): \Statamic\Forms\Form
    {
        return \Statamic\Facades\Form::find($this->handle)
            ?? throw new \Exception("Form with handle [{$this->handle}] cannot be found.");
    }

    #[Computed]
    public function fields(): Fields
    {
        return Fields::make($this->form, $this->getId())
            ->models($this->models)
            ->hydrated(fn ($fields) => $this->hydratedFields($fields))
            ->hydrate();
    }

    #[Computed(true)]
    public function honeypot(): Honeypot
    {
        return $this->fields->honeypot();
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
        return view("livewire-forms::{$this->view}");
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    protected function rules(): array
    {
        return $this->fields->validationRules();
    }

    protected function validationAttributes(): array
    {
        return $this->fields->validationAttributes();
    }

    #[On('field-conditions-updated')]
    public function submitFieldValue(string $field, bool $passesConditions): void
    {
        $this->fields->get($field)->always_save
            ? $this->fieldsToSubmit->put($field, true)
            : $this->fieldsToSubmit->put($field, $passesConditions);
    }

    protected function handleSubmission(): self
    {
        return $this
            ->runSubmittingFormHook()
            ->makeSubmission()
            ->runCreatedSubmissionFormHook()
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

    protected function runSubmittingFormHook(): self
    {
        $this->submittingForm();

        return $this;
    }

    protected function runCreatedSubmissionFormHook(): self
    {
        $this->createdSubmission($this->submission);

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

    protected function createdSubmission(Submission $submission): void
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

        $values = array_merge($this->normalizedData(), $assetIds);

        $processedValues = $this->form->blueprint()->fields()->addValues($values)->process()->values();

        $this->submission->data($processedValues);

        return $this;
    }

    protected function normalizedData(): array
    {
        return collect($this->data)->map(function ($value, $key) {
            $field = $this->fields->get($key);

            // Return early if a field can't be found, else we'll run into errors with the below code.
            if (is_null($field)) {
                return null;
            }

            // Only keep values of fields that should be submitted, e.g. if 'always_save' is on.
            if (! $this->fieldsToSubmit->get($field->handle)) {
                return null;
            }

            // Don't save the captcha response.
            if ($field->field()->type() === 'captcha') {
                return null;
            }

            // Cast to booleans if enabled in the config.
            if ($field->cast_booleans && in_array($value, ['true', 'false'])) {
                return Str::toBool($value);
            }

            // Cast to integers if the input type is 'number'.
            if ($field->input_type === 'number') {
                return (int) $value;
            }

            // Otherwise, just return the value.
            return $value;
        })->all();
    }

    protected function uploadedFiles(): array
    {
        // Only get the asset fields that contain data.
        $assetFields = array_intersect_key($this->data, $this->fields->getByType('assets')->all());

        // The assets fieldtype is expecting an array, even for `max_files: 1`, but we don't want to force that on the front end.
        return collect($assetFields)
            ->map(fn ($field) => Arr::wrap($field))
            ->all();
    }

    protected function handleSubmissionEvents(): self
    {
        $this->dispatch('formSubmitted');
        $formSubmitted = FormSubmitted::dispatch($this->submission);

        if ($formSubmitted === false) {
            throw new SilentFormFailureException();
        }

        $this->dispatch('submissionCreated');
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
        // Reset the form data.
        $this->data = $this->defaultData();

        // Reset asset fields using this trick: https://talltips.novate.co.uk/livewire/livewire-file-uploads-using-s3#removing-filename-from-input-field-after-upload
        $this->fields->getByType('assets')
            ->each(fn ($field) => $field->id($field->id().'_'.rand()));

        return $this;
    }

    protected function flashSuccess(): self
    {
        session()->flash('success', $this->successMessage());

        return $this;
    }

    public function successMessage(): string
    {
        return Lang::has("livewire-forms.{$this->handle}.success_message")
            ? __("livewire-forms.{$this->handle}.success_message")
            : __('livewire-forms::messages.success_message');
    }

    public function errorMessage(): string
    {
        return Lang::has("livewire-forms.{$this->handle}.error_message")
            ? trans_choice("livewire-forms.{$this->handle}.error_message", $this->getErrorBag()->count())
            : trans_choice('livewire-forms::messages.error_message', $this->getErrorBag()->count());
    }

    public function submitButtonLabel(): string
    {
        return Lang::has("livewire-forms.{$this->handle}.submit_button_label")
            ? __("livewire-forms.{$this->handle}.submit_button_label")
            : __('livewire-forms::messages.submit_button_label');
    }
}
