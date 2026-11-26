<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(Aerni\LivewireForms\Tests\TestCase::class)->in('.');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

// expect()->extend('toBeOne', function () {
//     return $this->toBe(1);
// });

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function surveyForm(): void
{
    \Statamic\Facades\Form::make('survey')->formFields([
        'fields' => [
            ['handle' => 'name', 'field' => ['type' => 'short_answer', 'validate' => 'required']],
            ['handle' => 'about', 'field' => ['type' => 'long_answer']],
            ['handle' => 'topic', 'field' => ['type' => 'dropdown', 'options' => ['support' => 'Support', 'sales' => 'Sales']]],
            ['handle' => 'color', 'field' => ['type' => 'multi_choice', 'options' => ['red' => 'Red', 'blue' => 'Blue']]],
            ['handle' => 'interests', 'field' => ['type' => 'checkboxes', 'options' => ['tech' => 'Tech', 'design' => 'Design']]],
            ['handle' => 'newsletter', 'field' => ['type' => 'toggle']],
            ['handle' => 'recommend', 'field' => ['type' => 'yes_no']],
            ['handle' => 'birthday', 'field' => ['type' => 'date_picker']],
            ['handle' => 'alarm', 'field' => ['type' => 'time_picker', 'seconds_enabled' => true]],
            ['handle' => 'age', 'field' => ['type' => 'number', 'min' => 0, 'max' => 120]],
            ['handle' => 'price', 'field' => ['type' => 'currency', 'currency' => 'USD']],
            ['handle' => 'country', 'field' => ['type' => 'dictionary', 'dictionary' => 'countries', 'max_items' => 1]],
            ['handle' => 'rating', 'field' => ['type' => 'star_rating']],
            ['handle' => 'score', 'field' => ['type' => 'opinion_scale']],
            ['handle' => 'priorities', 'field' => ['type' => 'ranking', 'options' => ['speed' => 'Speed', 'quality' => 'Quality']]],
            ['handle' => 'style', 'field' => ['type' => 'image_choice', 'options' => [['key' => 'light', 'label' => 'Light'], ['key' => 'dark', 'label' => 'Dark']]]],
            ['handle' => 'resume', 'field' => ['type' => 'upload']],
            ['handle' => 'address', 'field' => ['type' => 'group', 'fields' => [
                ['handle' => 'street', 'field' => ['type' => 'text']],
                ['handle' => 'city', 'field' => ['type' => 'text']],
            ]]],
            ['handle' => 'schedule', 'field' => ['type' => 'group', 'fields' => [
                ['handle' => 'starts_at', 'field' => ['type' => 'time_picker', 'validate' => 'required']],
            ]]],
            ['handle' => 'full_name', 'field' => ['type' => 'name']],
            ['handle' => 'email', 'field' => ['type' => 'email']],
            ['handle' => 'phone', 'field' => ['type' => 'phone']],
            ['handle' => 'website', 'field' => ['type' => 'website']],
            ['handle' => 'intro', 'field' => ['type' => 'heading', 'display' => 'About you', 'subheading' => 'Tell us more']],
            ['handle' => 'terms', 'field' => ['type' => 'paragraph', 'text' => 'Some fine print']],
            ['handle' => 'notice', 'field' => ['type' => 'banner', 'display' => 'Heads up', 'content' => 'Please **read** this', 'state' => 'warning']],
            ['handle' => 'gap', 'field' => ['type' => 'spacer']],
        ],
    ])->save();
}

function filledSurveyForm(array $values = []): \Livewire\Features\SupportTesting\Testable
{
    surveyForm();

    $component = \Livewire\Livewire::test(\Aerni\LivewireForms\Livewire\BaseForm::class, ['handle' => 'survey']);

    $component->set('submittableFields', $component->instance()->fields->map(fn () => true)->all());

    foreach ([
        'name' => 'Michael',
        'schedule' => ['starts_at' => '09:15'],
        ...$values,
    ] as $handle => $value) {
        $component->set("fields.{$handle}.value", $value);
    }

    return $component;
}
