<?php

use Aerni\LivewireForms\Fields;
use Aerni\LivewireForms\Livewire\BaseForm;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\Form as StatamicForm;
use Statamic\Forms\DeleteTemporaryFiles;
use Statamic\Forms\SubmissionResult;
use Statamic\Forms\SubmitForm;

beforeEach(function () {
    if (! class_exists(SubmitForm::class)) {
        $this->markTestSkipped('Requires a Statamic version that ships with the SubmitForm action.');
    }

    StatamicForm::make('survey')->formFields([
        'fields' => [
            ['handle' => 'name', 'field' => ['type' => 'short_answer', 'validate' => 'required']],
            ['handle' => 'about', 'field' => ['type' => 'long_answer']],
            ['handle' => 'topic', 'field' => ['type' => 'dropdown', 'options' => ['support' => 'Support', 'sales' => 'Sales']]],
            ['handle' => 'color', 'field' => ['type' => 'multi_choice', 'options' => ['red' => 'Red', 'blue' => 'Blue']]],
            ['handle' => 'interests', 'field' => ['type' => 'checkboxes', 'options' => ['tech' => 'Tech', 'design' => 'Design']]],
            ['handle' => 'newsletter', 'field' => ['type' => 'toggle']],
            ['handle' => 'recommend', 'field' => ['type' => 'yes_no']],
            ['handle' => 'birthday', 'field' => ['type' => 'date_picker']],
            ['handle' => 'alarm', 'field' => ['type' => 'time_picker']],
            ['handle' => 'age', 'field' => ['type' => 'number']],
            ['handle' => 'rating', 'field' => ['type' => 'star_rating']],
            ['handle' => 'score', 'field' => ['type' => 'opinion_scale']],
            ['handle' => 'priorities', 'field' => ['type' => 'ranking', 'options' => ['speed' => 'Speed', 'quality' => 'Quality']]],
            ['handle' => 'style', 'field' => ['type' => 'image_choice', 'options' => [['key' => 'light', 'label' => 'Light'], ['key' => 'dark', 'label' => 'Dark']]]],
            ['handle' => 'resume', 'field' => ['type' => 'upload']],
            ['handle' => 'address', 'field' => ['type' => 'group', 'fields' => [
                ['handle' => 'street', 'field' => ['type' => 'text']],
                ['handle' => 'city', 'field' => ['type' => 'text']],
            ]]],
            ['handle' => 'intro', 'field' => ['type' => 'heading', 'display' => 'About you', 'subheading' => 'Tell us more']],
            ['handle' => 'terms', 'field' => ['type' => 'paragraph', 'text' => 'Some fine print']],
            ['handle' => 'notice', 'field' => ['type' => 'banner', 'display' => 'Heads up', 'content' => 'Please read this']],
            ['handle' => 'gap', 'field' => ['type' => 'spacer']],
        ],
    ])->save();
});

afterEach(function () {
    StatamicForm::find('survey')?->delete();
});

it('renders a form containing every fieldtype', function () {
    Livewire::test(BaseForm::class, ['handle' => 'survey'])
        ->assertStatus(200);
});

it('resolves the field models of the new fieldtypes', function () {
    $fields = Livewire::test(BaseForm::class, ['handle' => 'survey'])
        ->instance()
        ->fields;

    expect($fields->get('recommend'))->toBeInstanceOf(Fields\Radio::class)
        ->and($fields->get('birthday'))->toBeInstanceOf(Fields\Date::class)
        ->and($fields->get('alarm'))->toBeInstanceOf(Fields\Time::class)
        ->and($fields->get('rating'))->toBeInstanceOf(Fields\StarRating::class)
        ->and($fields->get('score'))->toBeInstanceOf(Fields\OpinionScale::class)
        ->and($fields->get('priorities'))->toBeInstanceOf(Fields\Ranking::class)
        ->and($fields->get('style'))->toBeInstanceOf(Fields\ImageChoice::class)
        ->and($fields->get('resume'))->toBeInstanceOf(Fields\FormUpload::class)
        ->and($fields->get('address'))->toBeInstanceOf(Fields\Group::class)
        ->and($fields->get('intro'))->toBeInstanceOf(Fields\FormHeading::class)
        ->and($fields->get('terms'))->toBeInstanceOf(Fields\Html::class)
        ->and($fields->get('notice'))->toBeInstanceOf(Fields\Info::class);
});

it('persists a submission with the values of the new fieldtypes', function () {
    filledSurveyForm([
        'recommend' => 'yes',
        'birthday' => '1990-04-24',
        'alarm' => '07:30',
        'rating' => 4,
        'score' => 7,
        'priorities' => ['quality', 'speed'],
        'style' => 'dark',
        'address' => ['street' => 'Milson Road', 'city' => 'London'],
    ])
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    $submission = StatamicForm::find('survey')->submissions()->first();

    expect($submission->get('recommend'))->toBe('yes')
        ->and($submission->get('birthday'))->toBe('1990-04-24')
        ->and($submission->get('alarm'))->toBe('07:30')
        ->and($submission->get('rating'))->toBe(4)
        ->and($submission->get('score'))->toBe(7)
        ->and($submission->get('priorities'))->toBe(['quality', 'speed'])
        ->and($submission->get('style'))->toBe('dark')
        ->and($submission->get('address'))->toBe(['street' => 'Milson Road', 'city' => 'London']);
});

it('uploads files through the upload fieldtype', function () {
    /* Statamic deletes the temporary files of non-stored upload fields after the emails have been sent. */
    Bus::fake(DeleteTemporaryFiles::class);

    filledSurveyForm(['resume' => [UploadedFile::fake()->create('resume.pdf', 100)]])
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    $submission = StatamicForm::find('survey')->submissions()->first();

    expect($submission->get('resume'))->toBe(["{$submission->id()}/resume/resume.pdf"]);

    Storage::disk(config('statamic.system.file_uploads_disk', 'local'))
        ->assertExists("statamic/form-uploads/{$submission->id()}/resume/resume.pdf");
});

it('ignores the FormSubmitted event of other forms submitted in the same request', function () {
    StatamicForm::make('other')->formFields([
        'fields' => [
            ['handle' => 'name', 'field' => ['type' => 'short_answer']],
        ],
    ])->save();

    Event::listen(FormSubmitted::class, function (FormSubmitted $event) {
        if ($event->submission->form()->handle() === 'survey') {
            app(SubmitForm::class)->form(StatamicForm::find('other'))->submit(['name' => 'Intruder']);
        }
    });

    filledSurveyForm()
        ->call('submit')
        ->assertNotDispatched('form-submitted', fn ($event, $params) => $params['data']['name'] === 'Intruder')
        ->assertDispatched('form-submitted', fn ($event, $params) => $params['data']['name'] === 'Michael');

    StatamicForm::find('other')->delete();
});

it('shows the restriction message when the form is restricted', function () {
    tap(StatamicForm::find('survey'))
        ->set('close_date', now()->subDay()->format('Y-m-d H:i'))
        ->save();

    filledSurveyForm()
        ->call('submit')
        ->assertHasErrors('*');

    expect(StatamicForm::find('survey')->submissions())->toHaveCount(0);
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
        ->assertHasErrors($expectedErrorKey);
})->with([
    'field error' => [['name' => ['The name is invalid.']], 'fields.name.value'],
    'form-level error' => [['*' => ['This form must be submitted from an entry.']], '*'],
]);

function filledSurveyForm(array $values = []): \Livewire\Features\SupportTesting\Testable
{
    $component = Livewire::test(BaseForm::class, ['handle' => 'survey']);

    $component->set('submittableFields', $component->instance()->fields->map(fn () => true)->all());

    foreach (['name' => 'Michael', ...$values] as $handle => $value) {
        $component->set("fields.{$handle}.value", $value);
    }

    return $component;
}
