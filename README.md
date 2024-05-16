![Statamic](https://flat.badgen.net/badge/Statamic/5.0+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/livewire-forms/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/livewire-forms)

# Livewire Forms
This addon provides a powerful framework to use Statamic forms with Laravel Livewire. No more submitting your form with AJAX or dealing with funky client-side validation libraries. Livewire Forms is a powerhouse that will make your life soooo much easier!

## Features
- Use your Statamic form blueprints as a form builder
- Real-time validation with fine-grained control over each field
- No need for a client-side form validation library
- One source of truth for your validation rules
- Spam protection with Google reCAPTCHA v2 and honeypot field
- Support for display conditions set in your form blueprint
- Multi-site support; translate your form display labels, instructions, placeholders, etc.
- Configured and styled form views

## Installation
Install the addon using Composer:

```bash
composer require aerni/livewire-forms
```

NOTE: If you are [bundling Livewire assets yourself](https://livewire.laravel.com/docs/installation#manually-bundling-livewire-and-alpine) you will need to import Livewire Form's assets yourself:
```js
import "../../vendor/aerni/livewire-forms/resources/dist/js/livewire-forms";
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
    | Field Models
    |--------------------------------------------------------------------------
    |
    | You may change the model of each fieldtype with your own implementation.
    |
    */

    'models' => [
        Aerni\LivewireForms\Fieldtypes\Captcha::class => Aerni\LivewireForms\Fields\Captcha::class,
        Statamic\Fieldtypes\Assets\Assets::class => Aerni\LivewireForms\Fields\Assets::class,
        Statamic\Fieldtypes\Checkboxes::class => Aerni\LivewireForms\Fields\Checkboxes::class,
        Statamic\Fieldtypes\Hidden::class => Aerni\LivewireForms\Fields\Hidden::class,
        Statamic\Fieldtypes\Integer::class => Aerni\LivewireForms\Fields\Integer::class,
        Statamic\Fieldtypes\Radio::class => Aerni\LivewireForms\Fields\Radio::class,
        Statamic\Fieldtypes\Select::class => Aerni\LivewireForms\Fields\Select::class,
        Statamic\Fieldtypes\Spacer::class => Aerni\LivewireForms\Fields\Spacer::class,
        Statamic\Fieldtypes\Text::class => Aerni\LivewireForms\Fields\Text::class,
        Statamic\Fieldtypes\Textarea::class => Aerni\LivewireForms\Fields\Textarea::class,
        Statamic\Fieldtypes\Toggle::class => Aerni\LivewireForms\Fields\Toggle::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | The path under resources/views where the form views are published and loaded from.
    |
    */

    'view_path' => 'livewire/forms',

    /*
    |--------------------------------------------------------------------------
    | Default View
    |--------------------------------------------------------------------------
    |
    | The default view that is used if you don't specify one on the component.
    |
    */

    'view' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The default theme that is used if you don't specify one on the component.
    |
    */

    'theme' => 'default',

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
        'secret' => env('CAPTCHA_SECRET'),
    ],

];
```

## Commands

There are a number of helpful commands to help you create views, themes and components:

| Command                        | Description                            |
| -------------------------------| -------------------------------------- |
| `livewire-forms:setup`         | Step by step wizard to get you started |
| `livewire-forms:view {name?}`  | Create a new Livewire form view        |
| `livewire-forms:theme {name?}` | Create a new Livewire form theme       |
| `livewire-forms:component`     | Create a new Livewire form component   |

## Getting started

### Prerequisite

The views of this addon are styled with [Tailwind CSS](https://tailwindcss.com/) and [@tailwindcss/forms](https://github.com/tailwindlabs/tailwindcss-forms). However, you are free to change the views however you please.

### Run the setup command

Go ahead and run the `livewire-forms:setup` command in your console. It will guide you through creating your first [view](https://github.com/aerni/statamic-livewire-forms#views) and [theme](https://github.com/aerni/statamic-livewire-forms#themes). Optionally, you may also create a [component](https://github.com/aerni/statamic-livewire-forms#components) for complete control of your form.

```bash
php please livewire-forms:setup
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

Use the `view` and `theme` parameters if you want to use a view or theme that is different from the defaults defined in `config/livewire-forms.php`.

```blade
<!-- Antlers -->
{{ livewire:form :handle="field:handle" view="contact" theme="regular" }}

<!-- Blade -->
<livewire:form :handle="field:handle" view="contact" theme="regular">
```

#### Available Properties

| Property | Description                                       |
| -------- | ------------------------------------------------- |
| `handle` | The handle of the form you want to use (required) |
| `view`   | The component view you want to use (optional)     |
| `theme`  | The theme you want to use (optional)              |

## Views

Views are the entry point of your forms. You may use the same view for each form.

### Creating a view

Use the `livewire-forms:view` command to create a new view:

```bash
php please livewire-forms:view
```

### Autoloading

Views are autoloaded by the handle of a form. In the example below, it will try to load the `contact.blade.php` view. If it doesn't exist, it will fall back to the default view defined in `config/livewire-forms.php`.

```blade
{{ livewire:form handle="contact" }}
```

### Blade Directives

There are a couple of blade directives you may use in your form views. The directives are aware of the form's theme and will render the views accordingly.

| Directive                | Description                                                                      | View                      |
| ------------------------ | -------------------------------------------------------------------------------- | ------------------------- |
| `@formSection('handle')` | Render a specific form section, e.g., `@formSection('contact_information')`      | section.blade.php         |
| `@formField('handle')`   | Render a specific form field, e.g., `@formField('first_name')`                   | field.blade.php           |
| `@formView('view')`      | Render a specific view of the current theme, e.g., `@formView('messages.display')` | Whatever view you provide |

### Customization Example

Sometimes you need more control over the markup of your form. Rather than relying on the form's blueprint, you may decide to go fully custom and render individual fields using the `@formField` directive.

```blade
@formField('name')
```

You may also add or override field properties using an array as the second argument.

```blade
@formField('name', [
    'display' => 'Your Name',
    'tooltip' => 'Please enter your full name'
])
```

You can then access the properties in the field's view.

```blade
{{ $field->display }}
{{ $field->tooltip }}
```

## Themes

Themes allow you to fully customize each individual form view. You may have any number of themes.

### Creating a theme

Use the `livewire-forms:theme` command to create a new theme:

```bash
php please livewire-forms:theme
```

### Autoloading

Themes are autoloaded by the handle of a form. In the example below, it will try to load the views of the `contact` theme. If the theme doesn't exist, it will fall back to the default theme defined in `config/livewire-forms.php`. If a single view of the selected theme doesn't exist, it will fall back to the default theme for that particular view.

```blade
{{ livewire:form handle="contact" }}
```

> **Good to know:** Future releases of this addon will likely introduce breaking changes to your views. In that case, you will have to manually update your views according to the changes.

### Field Views

By default, each field will load the view by its type. For example, a `subscription` field of `type: radio` will load the `radio.blade.php` view.

Sometimes you may want to load a different view for a given field, like a fancy radio button group for selecting a subscription. Field views are autoloaded by the field's handle. In this example, you can simply create a `subscription.blade.php` view under the theme's `fields` folder to autoload your custom view.

You may manually override a field's view by adding `view: {the_name_of_the_view}` to the field's config in the blueprint.

## Components

Sometimes, you need more control over your form. For instance, if you want to dynamically populate a select field's options. There are a couple of concepts that help you customize your form experience.

#### Creating a component

Get started by creating a new component. The following example will create a new form component in `app/Livewire/ContactForm.php`

```bash
php please livewire-forms:component ContactForm
```

### Autoloading

Custom components are autoloaded by matching the class name with the form's handle. In the example below, it will try to load the `App\Livewire\ContactForm.php` component. If this component doesn't exist, it will fall back to the default form component.

```blade
<!-- Antlers -->
{{ livewire:form handle="contact" }}

<!-- Blade -->
<livewire:form handle="contact">
```

>**Note:** For the autoloading magic to work, the component's name needs to end with `Form`, e.g., `ContactForm.php.`

### Explicit Loading

You can also explicitly load a custom component by name like you would with any other Livewire component. This is necessary if you need to pass additional custom properties to the component.

```blade
<!-- Antlers -->
{{ livewire:contact-form my-custom-prop="value" }}

<!-- Blade -->
<livewire:contact-form my-custom-prop="value">
```

### Field Models

Each Statamic fieldtype is mapped to a Livewire Forms field model. Models are responsible for generating a field's properties like `view`, `display`, `instructions`, `options`, and so on. You can find the default mappings in `config/livewire-forms.php`.

For instance, all the fields of type `\Statamic\Fieldtypes\Select::class` are bound to the `\Aerni\LivewireForms\Fields\Select::class` model. A field property is created for each model method ending with `Property`, e.g. `optionsProperty()` will generate an `options` property.

To change a field's default model, simply change the binding in the `models` property in your component:

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

### Hooks

There are a couple of hooks that let you modify fields and submission data at various lifecycle steps.

#### Mounted Fields

Use this hook to modify the fields after they are mounted.

```php
public function mountedFields(Collection $fields): void
{
    $fields->get('name')->display('Your name');
}
```

#### Form Submitted

Use this hook to modify the form submission before it is processed by Statamic.

```php
public function formSubmitted(Submission $submission): void
{
    $title = $submission->augmentedValue('entry')->value()->title;

    $submission->set('entry_title', $title);
}
```

### Events

This addon dispatches the following Events.

#### Form Submitted

The `form-submitted` Livewire and `FormSubmitted` Statamic event is dispatched right after the Form Submitted hook. You can listen to this event as follows.

**Livewire**
```js
@script
<script>
    $wire.on('form-submitted', () => {
        //
    });
</script>
@endscript
```

**Statamic**
`Statamic\Events\FormSubmitted`

```php
public function handle(FormSubmitted $event)
{
    $event->submission; // The Submission object
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
    protected string $view = 'select_product';

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
namespace App\Livewire;

use Aerni\LivewireForms\Livewire\Form;

class ContactForm extends Form
{
    protected array $models = [
        'products' => \App\Fields\SelectProduct::class,
    ];
}
```

#### Using the mountedFields hook

Instead of defining a new field model, we can also achieve the same thing using the `mountedFields` hook.

```php
namespace App\Livewire;

use Aerni\LivewireForms\Livewire\Form;

class ContactForm extends Form
{
    public function mountedFields(Collection $fields): void
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

You can use any validation rule you want. Simply add it to the field in the form blueprint or use the blueprint builder in the CP. 

To validate against the value of another field, you need to get its value like in the following example:

```yaml
validate:
  - 'required_if:fields.newsletter.value,true'
```

### Real-time Validation

Real-time validation works out of the box by updating the field's value on [change event](https://livewire.laravel.com/docs/wire-model#updating-on-change-event). You may override this behavior by setting the `wire_model` parameter in the field's config.

```yaml
tabs:
  main:
    display: Main
    sections:
      -
        fields:
          -
            handle: email
            field:
              type: text
              wire_model: live.debounce.150ms
              validate:
                - required
                - email
```

To use Livewire's default behavior and defer all network requests until the form is submitted, you may set `wire_model: defer`.

### Validation Messages

You can customize the validation messages of your fields by creating a [custom form component](#components) and using either of the two methods below.

>**Note:** Make sure to add `data` in front of the field's handle.

#### Using the `$messages` property

```php
protected $messages = [
    'fields.name.value.required' => 'What is your name, darling?',
];
```

#### Using the `messages()` method

```php
protected function messages(): array
{
    return [
        'fields.name.value.required' => 'What is your name, darling?',
    ];
}
```

## Localization

There are a few default message strings, like the `submit button label` and `success message` that you might want to change. You can change the messages globally or on a per-form level.

### Globally

Publish the language files and change whatever message you'd like:

```bash
php artisan vendor:publish --tag=livewire-forms-translations
```

### Per Form

1. Create a file called `livewire-forms.php` for each of your localizations, e.g., `resources/lang/en/livewire-forms.php`.
2. Create an array with the handle of each form for which you want to change a message for.
3. Use the same keys that are used in the global language files. Note, that the messages in this file will take precedence over the messages in the global language file.

```php
return [

    'contact' => [
        'submit_button_label' => 'Contact now',
        'success_message' => 'Thanks for contacting us. We will be in touch.',
        'error_message' => 'There was an error with your submission:|There were :count errors with your submission:',
    ],

    'newsletter' => [
        'submit_button_label' => 'Signup now',
    ],

];
```

### Translating sections and fields

You can translate your field display labels, instructions, options, and placeholders using JSON files. Create a translation file for each language, e.g. `resources/lang/de.json`.

### Example

**Form Blueprint**
```yaml
tabs:
  main:
    display: Main
    sections:
      -
        display: Subscription
        instructions: 'Choose your subscription below'
        fields:
          -
            handle: subscription
            field:
              display: Subscription
              placeholder: 'Which subscription do you want?'
```

**Translation File**
```json
{
    "Subscription": "Abo",
    "Choose your subscription below": "Wähle dein Abo",
    "Which subscription do you want?": "Welches Abo möchtest du?",
}
```

## Captcha Fieldtype

This addon comes with a `Captcha` fieldtype that lets you add a `Google reCAPTCHA v2 (checkbox)` captcha to your form. The Captcha fieldtype is available in the form blueprint builder like any other fieldtype.

>**Note:** Make sure to add your captcha key and secret in your `.env` file.

## License
Livewire Forms is **commercial software** but has an open-source codebase. If you want to use it in production, you'll need to [buy a license from the Statamic Marketplace](https://statamic.com/addons/aerni/livewire-forms).
>Livewire Forms is **NOT** free software.

## Credits
Developed by[ Michael Aerni](https://www.michaelaerni.ch)
