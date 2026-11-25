<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Assets;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Statamic\Contracts\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Statamic\Forms\SubmitForm;

trait HandlesSubmission
{
    protected ?Submission $submission = null;

    protected function handleFormEvents(SubmitForm $action): void
    {
        Event::listen(FormSubmitted::class, function (FormSubmitted $event) use ($action) {
            // Forms in the same request should not cross-fire each other's hooks and events.
            if ($event->submission !== $action->submission()) {
                return;
            }

            $this->formSubmitted($event->submission);
            $this->dispatch('form-submitted', data: $event->submission->data());
        });
    }

    protected function submittableData(): array
    {
        return $this->submittableValues()
            ->reject(fn ($value, $handle) => $this->fields->get($handle) instanceof Assets)
            ->all();
    }

    protected function submittableFiles(): array
    {
        return $this->submittableValues()
            ->filter(fn ($value, $handle) => $this->fields->get($handle) instanceof Assets)
            ->filter()
            ->all();
    }

    /**
     * Rewrite Statamic's error keys to the Livewire field keys.
     */
    protected function withLivewireFieldKeys(ValidationException $exception): ValidationException
    {
        $messages = collect($exception->errors())
            ->reduce(function (array $messages, array $errors, string $key) {
                $handle = Str::before($key, '.');
                $tail = Str::after($key, '.');
                $tailIsIndex = ctype_digit($tail);
                $targetsField = ! str_contains($key, '.') || $tailIsIndex;

                if ($this->fields->has($handle) && $targetsField) {
                    // `name` becomes fields.name.value. An upload field reports each file as `resume.0`,
                    // and that index is dropped so it uses fields.resume.value too.
                    $livewireKey = "fields.{$handle}.value";
                } elseif ($this->fields->has($handle)) {
                    // `address.street` keeps its tail.
                    $livewireKey = "fields.{$handle}.value.{$tail}";
                } else {
                    // Any other key is left unchanged.
                    $livewireKey = $key;
                }

                // Messages that share a key are kept together.
                $messages[$livewireKey] = [
                    ...($messages[$livewireKey] ?? []),
                    ...$errors,
                ];

                return $messages;
            }, []);

        return ValidationException::withMessages($messages);
    }

    public function formSubmitted(Submission $submission): void
    {
        //
    }
}
