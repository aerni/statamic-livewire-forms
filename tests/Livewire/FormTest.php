<?php

use Aerni\LivewireForms\Livewire\BaseForm;
use Aerni\LivewireForms\Livewire\DynamicForm;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\Form as StatamicForm;
use Statamic\Forms\SubmissionResult;
use Statamic\Forms\SubmitForm;

beforeEach(function () {
    contactForm();
});

afterEach(function () {
    StatamicForm::find('contact')?->delete();
    StatamicForm::find('other')?->delete();
    StatamicForm::find('survey')?->delete();
});

it('renders dynamic-form successfully', function () {
    Livewire::test(DynamicForm::class, ['handle' => 'contact'])
        ->assertStatus(200);
});

it('renders basic-form successfully', function () {
    Livewire::test(BaseForm::class, ['handle' => 'contact'])
        ->assertStatus(200);
});

it('shows success and flashes the submission without persisting it when the honeypot is filled', function () {
    filledContactForm(['winnie' => 'spam'])
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    expect(session('_flash.new'))->toContain('submission');
    expect(StatamicForm::find('contact')->submissions())->toHaveCount(0);
});

it('flashes and persists a submission on successful submit', function () {
    filledContactForm()
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    expect(session('_flash.new'))->toContain('submission');
    expect(StatamicForm::find('contact')->submissions())->toHaveCount(1);
});

it('flashes the submission when FormSubmitted returns false without persisting it', function () {
    Event::listen(FormSubmitted::class, fn () => false);

    filledContactForm()
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    expect(session('_flash.new'))->toContain('submission');
    expect(StatamicForm::find('contact')->submissions())->toHaveCount(0);
});

it('ignores the FormSubmitted event of other forms submitted in the same request', function () {
    StatamicForm::make('other')->formFields([
        'fields' => [
            ['handle' => 'name', 'field' => ['type' => 'short_answer']],
        ],
    ])->save();

    Event::listen(FormSubmitted::class, function (FormSubmitted $event) {
        if ($event->submission->form()->handle() === 'contact') {
            app(SubmitForm::class)->form(StatamicForm::find('other'))->submit(['name' => 'Intruder']);
        }
    });

    filledContactForm()
        ->call('submit')
        ->assertNotDispatched('form-submitted', fn ($event, $params) => $params['data']['name'] === 'Intruder')
        ->assertDispatched('form-submitted', fn ($event, $params) => $params['data']['name'] === 'Michael');
});

it('shows the restriction message when the form is restricted', function () {
    tap(StatamicForm::find('contact'))
        ->set('close_date', now()->subDay()->format('Y-m-d H:i'))
        ->save();

    filledContactForm()
        ->call('submit')
        ->assertHasErrors('*');

    expect(StatamicForm::find('contact')->submissions())->toHaveCount(0);
});

it('maps the validation errors of the submit action to their Livewire error keys', function (array $messages, string $expectedErrorKey) {
    app()->bind(SubmitForm::class, fn () => new class($messages) extends SubmitForm
    {
        public function __construct(protected array $messages) {}

        public function submit(array $data, array $files = []): SubmissionResult
        {
            throw ValidationException::withMessages($this->messages);
        }
    });

    filledSurveyForm()
        ->call('submit')
        ->assertOnlyHasErrors($expectedErrorKey);
})->with([
    'field error' => [['name' => ['The name is invalid.']], 'fields.name.value'],
    'form-level error' => [['*' => ['This form must be submitted from an entry.']], '*'],
    'file error' => [['resume.0' => ['The resume failed to upload.']], 'fields.resume.value'],
    'indexed field error' => [['interests.0' => ['The interests are invalid.']], 'fields.interests.value'],
    'nested field error' => [['address.street' => ['The street is invalid.']], 'fields.address.value.street'],
]);

it('maps every indexed upload error onto the upload field', function () {
    app()->bind(SubmitForm::class, fn () => new class extends SubmitForm
    {
        public function submit(array $data, array $files = []): SubmissionResult
        {
            throw ValidationException::withMessages([
                'resume.0' => ['The resume must be a PDF.'],
                'resume.1' => ['The resume failed to upload.'],
            ]);
        }
    });

    filledSurveyForm()
        ->call('submit')
        ->assertOnlyHasErrors([
            'fields.resume.value' => [
                'The resume must be a PDF.',
                'The resume failed to upload.',
            ],
        ]);
});

function contactForm(): void
{
    StatamicForm::make('contact')->formFields([
        'fields' => [
            ['handle' => 'name', 'field' => ['type' => 'short_answer']],
            ['handle' => 'email', 'field' => ['type' => 'short_answer']],
            ['handle' => 'message', 'field' => ['type' => 'long_answer']],
        ],
    ])->honeypot('winnie')->save();
}

function filledContactForm(array $values = []): \Livewire\Features\SupportTesting\Testable
{
    $component = Livewire::test(BaseForm::class, ['handle' => 'contact'])
        ->set('submittableFields', [
            'name' => true,
            'email' => true,
            'message' => true,
            'winnie' => true,
        ]);

    foreach ([
        'name' => 'Michael',
        'email' => 'michael@example.com',
        'message' => 'Hello',
        ...$values,
    ] as $handle => $value) {
        $component->set("fields.{$handle}.value", $value);
    }

    return $component;
}
