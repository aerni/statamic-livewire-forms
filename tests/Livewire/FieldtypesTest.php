<?php

use Aerni\LivewireForms\Fields;
use Aerni\LivewireForms\Livewire\BaseForm;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Statamic\Facades\Form as StatamicForm;
use Statamic\Forms\DeleteTemporaryFiles;

beforeEach(function () {
    surveyForm();
});

afterEach(function () {
    StatamicForm::find('survey')?->delete();
});

it('renders every fieldtype', function () {
    $component = Livewire::test(BaseForm::class, ['handle' => 'survey'])
        ->assertSuccessful()
        ->assertSee('autocomplete="name"', false)
        ->assertSee('autocomplete="email"', false)
        ->assertSee('autocomplete="tel"', false)
        ->assertSee('autocomplete="url"', false)
        ->assertSee('type="email"', false)
        ->assertSee('type="tel"', false)
        ->assertSee('type="url"', false)
        ->assertSee('min="0"', false)
        ->assertSee('max="120"', false)
        ->assertSee('step="0.01"', false)
        ->assertSee('step="1"', false)
        ->assertSee('<strong>read</strong>', false)
        ->assertSee('bg-yellow-50', false)
        ->assertSee('$wire.get(', false);

    $fields = $component->instance()->fields;
    $rules = $fields->get('schedule')->rules['fields.schedule.value.starts_at'];

    expect($fields->except('honeypot')->map(fn ($field) => $field::class)->all())->toEqual([
        'name' => Fields\Text::class,
        'about' => Fields\Textarea::class,
        'topic' => Fields\Select::class,
        'color' => Fields\Radio::class,
        'interests' => Fields\Checkboxes::class,
        'newsletter' => Fields\Toggle::class,
        'recommend' => Fields\Radio::class,
        'birthday' => Fields\Date::class,
        'alarm' => Fields\Time::class,
        'age' => Fields\Integer::class,
        'price' => Fields\FloatVal::class,
        'country' => Fields\Dictionary::class,
        'rating' => Fields\StarRating::class,
        'score' => Fields\OpinionScale::class,
        'priorities' => Fields\Ranking::class,
        'style' => Fields\ImageChoice::class,
        'resume' => Fields\FormUpload::class,
        'address' => Fields\Group::class,
        'schedule' => Fields\Group::class,
        'full_name' => Fields\Text::class,
        'email' => Fields\Text::class,
        'phone' => Fields\Text::class,
        'website' => Fields\Text::class,
        'intro' => Fields\FormHeading::class,
        'terms' => Fields\Html::class,
        'notice' => Fields\Info::class,
        'gap' => Fields\Spacer::class,
    ])
        ->and($rules)->toContain('date_format:H:i')
        ->and(collect($rules)->every(fn ($rule) => is_string($rule)))->toBeTrue();
});

it('persists a submission with every fieldtype', function () {
    filledSurveyForm([
        'about' => 'Hello there',
        'topic' => 'support',
        'color' => 'blue',
        'interests' => ['tech', 'design'],
        'newsletter' => true,
        'recommend' => 'yes',
        'birthday' => '1990-04-24',
        'alarm' => '07:30:00',
        'age' => 32,
        'price' => 19.99,
        'country' => 'GBR',
        'rating' => 4,
        'score' => 7,
        'priorities' => ['quality', 'speed'],
        'style' => 'dark',
        'address' => ['street' => 'Milson Road', 'city' => 'London'],
        'full_name' => 'Michael',
        'email' => 'michael@example.com',
        'phone' => '5551234567',
        'website' => 'https://example.com',
    ])
        ->call('submit')
        ->assertSuccessful()
        ->assertSee('The form was successfully submitted!');

    $submission = StatamicForm::find('survey')->submissions()->first();

    expect($submission->get('name'))->toBe('Michael')
        ->and($submission->get('about'))->toBe('Hello there')
        ->and($submission->get('topic'))->toBe('support')
        ->and($submission->get('color'))->toBe('blue')
        ->and($submission->get('interests'))->toBe(['tech', 'design'])
        ->and($submission->get('newsletter'))->toBeTrue()
        ->and($submission->get('recommend'))->toBe('yes')
        ->and($submission->get('birthday'))->toBe('1990-04-24')
        ->and($submission->get('alarm'))->toBe('07:30:00')
        ->and($submission->get('age'))->toBe(32)
        ->and($submission->get('price'))->toBe(19.99)
        ->and($submission->get('country'))->toBe('GBR')
        ->and($submission->get('rating'))->toBe(4)
        ->and($submission->get('score'))->toBe(7)
        ->and($submission->get('priorities'))->toBe(['quality', 'speed'])
        ->and($submission->get('style'))->toBe('dark')
        ->and($submission->get('address'))->toBe(['street' => 'Milson Road', 'city' => 'London'])
        ->and($submission->get('schedule'))->toBe(['starts_at' => '09:15'])
        ->and($submission->get('full_name'))->toBe('Michael')
        ->and($submission->get('email'))->toBe('michael@example.com')
        ->and($submission->get('phone'))->toBe('5551234567')
        ->and($submission->get('website'))->toBe('https://example.com');
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
