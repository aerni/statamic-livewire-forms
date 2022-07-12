![Statamic](https://flat.badgen.net/badge/Statamic/3.3.12+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/livewire-forms/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/livewire-forms)

# Livewire Forms
This addon provides a powerful framework to use Statamic forms with Laravel Livewire. No more submitting your form with AJAX or dealing with funky client-side validation libraries. Livewire Forms is a powerhouse that will make your life soooo much easier!

## Features
- Use your Statamic form blueprints as a form builder
- Realtime validation with fine-grained control over each field
- No need for a client-side form validation library
- One source of truth for your validation rules
- Spam protection with Google reCAPTCHA v2 and honeypot field
- Support for display conditions set in your form blueprint
- Multi-site support; translate your form labels, instructions, placeholders, etc.
- Configured and styled form views

## Installation
Install the addon using Composer:

```bash
composer require aerni/livewire-forms
```

Publish the config of the package (optional):

```bash
php please vendor:publish --tag=livewire-forms-config
```

The following config will be published to `config/livewire-forms.php`:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Default View
    |--------------------------------------------------------------------------
    |
    | This view will be used if you don't specify one on the component.
    |
    */

    'view' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | This theme will be used if you don't specify one on the component.
    |
    */

    'theme' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Field Models
    |--------------------------------------------------------------------------
    |
    | You may change the model of each fieldtype with your own implementation.
    |
    */

    'models' => [
        \Aerni\LivewireForms\Fieldtypes\Captcha::class => \Aerni\LivewireForms\Fields\Captcha::class,
        \Statamic\Fieldtypes\Assets\Assets::class => \Aerni\LivewireForms\Fields\Assets::class,
        \Statamic\Fieldtypes\Checkboxes::class => \Aerni\LivewireForms\Fields\Checkbox::class,
        \Statamic\Fieldtypes\Integer::class => \Aerni\LivewireForms\Fields\Integer::class,
        \Statamic\Fieldtypes\Radio::class => \Aerni\LivewireForms\Fields\Radio::class,
        \Statamic\Fieldtypes\Select::class => \Aerni\LivewireForms\Fields\Select::class,
        \Statamic\Fieldtypes\Text::class => \Aerni\LivewireForms\Fields\Text::class,
        \Statamic\Fieldtypes\Textarea::class => \Aerni\LivewireForms\Fields\Textarea::class,
        \Statamic\Fieldtypes\Toggle::class => \Aerni\LivewireForms\Fields\Toggle::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Realtime Validation
    |--------------------------------------------------------------------------
    |
    | A boolean to globally enable/disable realtime validation.
    |
    */

    'realtime' => true,

    /*
    |--------------------------------------------------------------------------
    | Captcha Configuration
    |--------------------------------------------------------------------------
    |
    | Add the credentials for your captcha.
    | This addon currently supports Google reCAPTCHA v2 (checkbox).
    |
    */

    'captcha' => [
        'key' => env('CAPTCHA_KEY'),
        'secret' => env('CAPTCHA_SECRET')
    ],

];
```

## Commands

There are a number of helpful commands to help you create views, themes and components:

| Command                            | Description                            |
| ---------------------------------- | -------------------------------------- |
| `livewire-forms:setup`             | Step by step wizard to get you started |
| `livewire-forms:view {name?}`      | Create a new Livewire form view        |
| `livewire-forms:theme {name?}`     | Create a new Livewire form theme       |
| `livewire-forms:component {name?}` | Create a new Livewire form component   |

## Getting started

### Prerequisite

This addon provides configured and styled form views for all Statamic form fieldtypes. The components are styled with [Tailwind CSS](https://tailwindcss.com/) and make use of the [@tailwindcss/forms](https://github.com/tailwindlabs/tailwindcss-forms) plugin. If you want to use the default styling, you'll need a working Tailwind setup.

### Run the setup command

Go ahead and run the following command in your console. It will guide you through creating your first [view](https://github.com/aerni/statamic-livewire-forms#views) and [theme](https://github.com/aerni/statamic-livewire-forms#themes). Optionally, you may also create a [component](https://github.com/aerni/statamic-livewire-forms#components) to customize the form's behavior.

```bash
php please livewire-forms:setup
```

### Setup your layout

Add the Livewire `styles` in the `head`, and the `scripts` before the closing `body` tag in your template:

```blade
<head>
    <!-- Antlers -->
    {{ livewire:styles }}

    <!-- Blade -->
    @livewireStyles
</head>

<body>
    <!-- Antlers -->
    {{ livewire:scripts }}

    <!-- Blade -->
    @livewireScripts
</body>
```

### Render the form

Add the Livewire form component to your template and provide the handle of the Statamic form.

```blade
<!-- Antlers -->
{{ livewire:form handle="contact" }}

<!-- Blade -->
<livewire:form handle="contact">
```

You can also dynamically render a form that was selected via Statamic's Form fieldtype:

```blade
<!-- Antlers -->
{{ livewire:form :handle="field:handle" }}

<!-- Blade -->
<livewire:form :handle="field:handle">
```

Use the `view` and `theme` parameter if you want to use a view or theme that is different to the one defined in `config/livewire-forms.php`.

```blade
<!-- Antlers -->
{{ livewire:form :handle="field:handle" view="contact" theme="regular" }}

<!-- Blade -->
<livewire:form :handle="field:handle" view="contact" theme="regular">
```

#### Available Properties

| Property | Description                                        |
| -------- | -------------------------------------------------- |
| `handle` | The handle of the form you want to use (required)  |
| `view`   | The component view you want to use                 |
| `theme`  | The theme you want to use                          |

## Views

Use the following command to create a new view:

```bash
php please livewire-forms:view
```

This is the default view. You may customize it to your liking.

```blade
<form wire:submit.prevent="submit" class="w-full max-w-2xl">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
        @formFields
        @formHoneypot
        @formSubmit
        @formErrors
        @formSuccess
    </div>
</form>
```

### Blade Directives

There are a couple of blade directives you may use in your form views. Each directive renders a view inside the current theme.

| Directive              | Description                                      | View               |
| ---------------------- | ------------------------------------------------ | ------------------ |
| `@formFields`          | Loop through and render all form fields          | fields.blade.php   |
| `@formField('handle')` | Render a specific form field                     | field.blade.php    |
| `@formGroups`          | Loop through and render all form fields by group | groups.blade.php   |
| `@formGroup('group')`  | Render a specific from field group               | group.blade.php    |
| `@formHoneypot`        | Render the form honeypot field                   | honeypot.blade.php |
| `@formSubmit`          | Render the form submit button                    | submit.blade.php   |
| `@formErrors`          | Render the form validation errors                | errors.blade.php   |
| `@formSuccess`         | Render the form success message                  | success.blade.php  |

### Customization Example

Sometimes you need more control over the markup of your form. If you decide to go completely custom, you can render single fields using the `@formField` directive. You may also add or override field properties using an array as the second argument.

```blade
@formField('name', [
    'view' => 'nameInput',
    'tooltip' => 'Please enter your full name'
])
```

Use the properties in the field's view like this:

```blade
{{ $field->view }}
{{ $field->tooltip }}
```

>**Note:** The `view` property will look for the view in the theme's fields directory: `{theme}/fields/{view}.blade.php`.

>**Note:** There are a few properties such as options, default and conditions that won't work correctly when assigned in the view. This is due to the hydration lifecycle of Livewire. If you want to change those properties, you should create a custom component instead.

## Themes

Themes allow you to customize the style and logic of your form fields. You may have any number of themes and use them for any of your forms. If a field's view doesn't exist in the configured theme, it will fall back to the default theme. You can set the default theme in `config/livewire-forms.php`.

Use the following command to create a new theme:

```bash
php please livewire-forms:theme
```

> **Important:** It's very likely that future releases will introduce breaking changes to the theme views. If that happens, you will have to manually update your themes.

## Components

Sometimes you need more control over your form. For instance, if you want to dynamically populate a select field's options. Or if you have multiple radio fields that need different styling. There are a couple of concepts that help you customize your form experience.

Get started by creating a new component. The following example will create a new form component in `app/Http/Livewire/ContactForm.php`

```bash
php please livewire-forms:component ContactForm
```

### Render the component

Livewire Forms is smart enough to autoload custom components by matching the class name with the form's handle. The following example will look for a `App\Http\Livewire\ContactForm.php` component. If there is no class with that name, the default form component will be loaded instead.

```blade
<!-- Antlers -->
{{ livewire:form handle="contact" }}

<!-- Blade -->
<livewire:form handle="contact">
```

>**Note:** The component's name needs to end with `Form`. This is necessary for Livewire Forms to do its autoloading magic.

### Field Models

Field models are responsible for generating a field's properties like `view`, `label`, and `rules`. For instance, all the fields of type `\Statamic\Fieldtypes\Select::class` are bound to the `\Aerni\LivewireForms\Fields\Select::class` model. A field property is created for each model method ending with `Property`, e.g. `optionsProperty()` will generate an `options` property.

To change a fields default model, simply change the binding in the `models` property in your component:

```php
protected array $models = [
    \Statamic\Fieldtypes\Select::class => \App\Fields\Select::class,
];
```

If you want to change a model for a specific field only, simply use the field's handle as the key instead:

```php
protected array $models = [
    'products' => \App\Fields\SelectProduct::class,
];
```

>**Tip:** You may change the default bindings in `config/livewire-forms.php`. If you have a fieldtype that's not supported by this addon, simply create a new model and add the binding to the config.

### Callbacks & Hooks

There are a couple of callbacks and hooks that let you modify fields and data at various lifecycle steps.

#### Hydrated Fields

Use this callback to modify the fields before they are rendered, e.g. a field's label. This is often the simpler route when changing a single thing, rather than adding a new field model binding.

```php
protected function hydratedFields(Fields $fields): void
{
    $fields->get('name')->label('Your name');
}
```

#### Submitting Form

Use this hook to change data before the form submission is created.

```php
protected function submittingForm(): void
{
    $this->data['full_name'] = "{$this->data['first_name']} {$this->data['name']}";
}
```

#### Submitted Form

Use this hook to perform an action after the form has been submitted.

```php
protected function submittedForm(): void
{
    Newsletter::subscribe($this->data['email']);
}
```

### Customization Example

In the following example we want to dynamically generate the options of a select field based on the entries of a Statamic collection. We also want to change the view of the field because the design needs to be different to a regular select field. There are two ways to achieve our task. We can either create a `custom field model` or use the `hydratedFields` callback. Choose whichever route feels better to you.

#### Using a custom field model

We start by creating a new `SelectProduct` field model class that extends the default `Select` model class. We then override the `optionsProperty` method to return our options from a collection. We also assign a different view using the `$view` class property.

```php
namespace App\Fields;

use Aerni\LivewireForms\Fields\Select;
use Statamic\Facades\Entry;

class SelectProduct extends Select
{
    protected static string $view = 'select_product';

    public function optionsProperty(): array
    {
        return Entry::whereCollection('products')
            ->mapWithKeys(fn ($product) => [$product->slug() => $product->get('title')])
            ->all();
    }
}
```

Next, we need to tell the form which field we want to use the `SelectProduct` model for. In our case, we only want to use the `SelectProduct` model for the select field with the handle `products`.

```php
namespace App\Http\Livewire;

use Aerni\LivewireForms\Http\Livewire\BaseForm;

class ContactForm extends BaseForm
{
    protected array $models = [
        'products' => \App\Fields\SelectProduct::class,
    ];
}
```

#### Using the hydratedFields callback

Instead of defining a new field model, we can also achieve the same thing using the `hydratedFields` callback.

```php
namespace App\Http\Livewire;

use Aerni\LivewireForms\Http\Livewire\BaseForm;

class ContactForm extends BaseForm
{
    protected function hydratedFields(Fields $fields): void
    {
        $options = Entry::whereCollection('products')
            ->mapWithKeys(fn ($product) => [$product->slug() => $product->get('title')])
            ->all();

        $fields->get('products')
            ->options($options)
            ->view('select_product');
    }
}
```

#### Render the component

Lastly, we need to render our new `ContactForm` component in the template.

```blade
<!-- Antlers -->
{{ livewire:form handle="contact" }}

<!-- Blade -->
<livewire:form handle="contact">
```

## Validation

### Validation Rules

You can use any validation rule you want. Simply add it to the field in the form blueprint or use the blueprint builder in the CP. If you have validation rules like `required_if`, make sure to prefix the field with `data`.

```yaml
validate:
  - 'required_if:data.newsletter,true'
```

### Validation Messages

You can customize the validation messages of any field. Simply follow the instructions in the [Livewire docs](https://laravel-livewire.com/docs/2.x/input-validation). Just make sure to add `data` in front of the field's handle.

```php
protected $messages = [
    'data.name.required' => 'What is your name darling?',
];
```

### Realtime Validation

You can configure real-time validation on three levels. In the config file, on the form, and on the form field. Each level will override the configuration of the previous level.

#### In the config

A boolean in `config/livewire-forms.php` to globally enable/disable realtime validation:

```php
'realtime' => true,
```

#### On the form

A boolean in a form's blueprint to enable/disable realtime validation for the whole form:

```yaml
sections:
  main:
    display: Main
    realtime: false
    fields:
      -
        handle: email
        ...
```

#### On the form field

A boolean in a form's blueprint to enable/disable realtime validation for a specific field:

```yaml
sections:
  main:
    display: Main
    fields:
      -
        handle: email
        field:
          ...
          realtime: true
          validate:
            - required
            - email
```

Sometimes you may want to only validate certain rules in realtime. You may provide an array with the rules you want to validate in realtime instead of a boolean:

```yaml
sections:
  main:
    display: Main
    fields:
      -
        handle: email
        field:
          ...
          realtime:
            - required
          validate:
            - required
            - email
```

## Field configuration

There are a couple of configuration options for your form fields:

| Parameter       | Type                                    | Supported by                   | Description                                                                                                                            |
| :-------------- | :-------------------------------------- | :----------------------------- | :------------------------------------------------------------------------------------------------------------------------------------- |
| `autocomplete`  | `string`                                | `input`, `textarea`, `select`  | Set the field's [autocomplete](https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/autocomplete) attribute. Defaults to `on`. |
| `cast_booleans` | `boolean`                               | `radio`, `select`              | String values of `true` and `false` will be saved as booleans. |
| `default`       | `array`, `boolean`, `integer`, `string` | All fieldtypes except `assets` | Set the field's default value |
| `group`         | `string`                                | All fieldtypes                 | Group your fields when using the `@formGroups` and `@formGroup('group')` directives in your view. |
| `inline`        | `boolean`                               | `checkboxes`, `radio`          | Set to `true` to display the fields inline |
| `placeholder`   | `string`                                | `input`, `textarea`            | Set the field's placeholder value |
| `show_label`    | `boolean`                               | All fieldtypes                 | Set to `false` to hide the field's label and instructions. |
| `width`         | `integer`                               | All fieldtypes                 | Set the desired width of the field. |

## Translating fields

You can translate your field labels, instructions, options, and placeholders using JSON files. Create a translation file for each language, e.g. `resources/lang/de.json`.

### Example

**Form Blueprint**
```yaml
sections:
  main:
    display: Main
    fields:
      -
        display: Colors
        placeholder: 'What is your favorite color?'
        ...
```

**Translation File**
```json
{
    "Colors": "Farben",
    "What is your favorite color?": "Was ist deine Lieblingsfarbe?",
}
```

## Captcha Fieldtype

This addon comes with a `Captcha` fieldtype that lets you add a `Google reCAPTCHA v2 (checkbox)` captcha to your form. The Captcha fieldtype is available in the form blueprint builder like any other fieldtype.

>**Note:** Make sure to add your captcha key and secret in your `.env` file.

## Events

This addon dispatches the following Events. Learn more about [Statamic Events](https://statamic.dev/extending/events) and [Livewire Events](https://laravel-livewire.com/docs/2.x/events) events.

### FormSubmitted

Dispatched when a Form is submitted on the front-end before the Submission is created.

#### Statamic

`Statamic\Events\FormSubmitted`

```php
public function handle(FormSubmitted $event)
{
    $event->submission; // The Submission object
}
```

#### Livewire

`formSubmitted`

```js
// JavaScript Example

Livewire.on('formSubmitted', () => {
    ...
})
```

### SubmissionCreated

Dispatched after a form submission has been created. This happens after a form has been submitted on the front-end.

#### Statamic

`Statamic\Events\SubmissionCreated`

```php
public function handle(SubmissionCreated $event)
{
    $event->submission;
}
```

#### Livewire

`submissionCreated`

```js
// JavaScript Example

Livewire.on('submissionCreated', () => {
    ...
})
```

## License
Livewire Forms is **commercial software** but has an open-source codebase. If you want to use it in production, you'll need to [buy a license from the Statamic Marketplace](https://statamic.com/addons/aerni/livewire-forms).
>Livewire Forms is **NOT** free software.

## Credits
Developed by[ Michael Aerni](https://www.michaelaerni.ch)
