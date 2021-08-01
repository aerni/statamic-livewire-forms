![Statamic](https://flat.badgen.net/badge/Statamic/3.0+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/livewire-forms/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/livewire-forms)

# Livewire Forms
This addon provides a powerful Statamic forms framework for Laravel Livewire. No more submitting your form with AJAX or dealing with funky client-side validation libraries. Livewire Forms is a powerhouse that will make your life soooo much easier!

## Features
- Realtime validation with fine-grained control over each field
- No need for a client-side form validation library
- One source of truth for your validation rules
- No redirects after the form was submitted
- Spam protection with a simple honeypot field and optional Google reCAPTCHA v2
- Use your Statamic form blueprint as a form builder
- Multi-site support; translate your form labels, instructions, placeholders, etc.
- Configured and styled form views in Antlers and Blade

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
        'enabled' => false,
        'key' => env('CAPTCHA_KEY'),
        'secret' => env('CAPTCHA_SECRET')
    ],

];
```

### Default form views

The default form views are styled with [Tailwind CSS](https://tailwindcss.com/). If you want to use the default styling, you need a working Tailwind setup with the [@tailwindcss/forms](https://github.com/tailwindlabs/tailwindcss-forms) plugin.

### Publish form views

You can also publish the form views to make the markup and styling your own:

```bash
# Publish the Antlers views
php please vendor:publish --tag=livewire-forms-antlers

# Publish the Blade views
php please vendor:publish --tag=livewire-forms-blade
```

The views will be published to `views/vendor/livewire-forms`.

> **Warning:** Future releases might introduce new functionality and come with updated views. If you choose to publish the views you are on your own and have to manually update views yourself.

## Basic usage

### 1. Create a Statamic form

Go ahead and create a Statamic form in the Control Panel.

### 2. Include Styles and Scripts

Add the Livewire `styles` in the `head`, and the `scripts` before the closing `body` tag in your template.

```html
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

If you want to use `Google reCAPTCHA v2` you also need to add the captcha scripts before the closing `body` tag in your template.

```html
<body>
    <!-- Antlers -->
    {{ captcha:scripts }}

    <!-- Blade -->
    @captchaScripts
</body>
```

### 3. Create a Livewire form view

Run the following command and follow the instructions to create a Livewire view for your Statamic form. The form view will be published to `views/livewire/forms/my-form-handle.{antlers.html|blade.php}`.

```bash
php please make:livewire-form
```

You may also choose to publish the default form views to change the markup and styling of the form fields. The views will be published to `views/vendor/livewire-forms`.

### 4. Render the form

Include the Livewire form component in your template and provide the handle of the Statamic form. This will automatically load the corresponding form view.

```html
<!-- Antlers -->
{{ livewire:form form="contact" }}

<!-- Blade -->
<livewire:form form="contact">
```

You can also dynamically render a form that was selected via Statamic's `form` fieldtype:

```html
<!-- Antlers -->
{{ livewire:form :form="field:handle" }}

<!-- Blade -->
<livewire:form :form="field:handle">
```

You may also use the same view for every form by passing the name of the view to the `view` parameter:

```html
<!-- Antlers -->
{{ livewire:form :form="field:handle" view="default" }}

<!-- Blade -->
<livewire:form :form="field:handle" view="default">
```

The view is expected to be in the `views/livewire/forms` directory. But you may specify a subdirectory like so:

```html
<!-- Antlers -->
{{ livewire:form :form="field:handle" view="contact/default" }}

<!-- Blade -->
<livewire:form :form="field:handle" view="contact/default">
```

## Customizing the form view

Sometimes you need more control over the markup of your form, eg. to group specific fields in a `<fieldset>`. You can include single fields like this:

```html
<!-- Antlers -->
{{ partial src="livewire-forms::fields" field="name" }}

<!-- Blade -->
@include('livewire-forms::fields', [
    'field' => $fields['name'],
])
```

## Translating your fields

You can translate your field labels, instructions, options, and placeholders using JSON files. Create a translation file for each language you want to translate, e.g. `resources/lang/de.json`.

### Example
```yaml
# resources/blueprints/forms/contact.yaml

sections:
  main:
    display: Main
    fields:
      -
        display: Colors
        placeholder: 'What is your favorite color?'
        ...
```

```json
// resources/lang/de.json

{
    "Colors": "Farben",
    "What is your favorite color?": "Was ist deine Lieblingsfarbe?",
}
```

## Captcha

You can configure the captcha on two levels. In the config file and on the form itself. The config on the form will override the global config.

### 1. In the config
A boolean to globally enable/disable the captcha.

```php
// config/livewire-forms.php

'captcha' => [
    'enabled' => true,
]
```

### 2. On the form
A boolean to enable/disable the captcha for a specific form.

```yaml
# resources/blueprints/forms/contact.yaml

sections:
  main:
    display: Main
    captcha: true
    fields:
      -
        handle: email
        ...
```

## Realtime validation

You can configure realtime validation on three levels. In the config file, on the form, and on the form field. Each level will override the configuration of the previous level.

### 1. In the config
A boolean to globally enable/disable realtime validation.

```php
// config/livewire-forms.php

'realtime' => true,
```

### 2. On the form
A boolean to enable/disable realtime validation for a specific form.

```yaml
# resources/blueprints/forms/contact.yaml

sections:
  main:
    display: Main
    realtime: false
    fields:
      -
        handle: email
        ...
```

### 3. On the form field
A boolean to enable/disable realtime validation for the field:

```yaml
# resources/blueprints/forms/contact.yaml

sections:
  main:
    display: Main
    fields:
      -
        handle: email
        field:
          ...
          validate:
            - required
            - email
          realtime: true
```

Sometimes you may want to only validate certain rules in realtime. You may provide an array with the rules you want to validate in realtime instead of a boolean:

```yaml
# resources/blueprints/forms/contact.yaml

sections:
  main:
    display: Main
    fields:
      -
        handle: email
        field:
          ...
          validate:
            - required
            - email
          realtime:
            - required
```

## Form field configuration

There are multiple configuration options for your form fields:

| Parameter       | Type                                    | Supported by          | Description                |
| :-------------- | :-------------------------------------- | :-------------------- | :------------------------- |
| `autocomplete`  | `string`                                | `input`               | Set the field's [autocomplete](https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/autocomplete) attribute |
| `cast_booleans` | `boolean`                               | All fieldtypes        | Save the field value as a boolean |
| `default`       | `array`, `boolean`, `integer`, `string` | All fieldtypes        | Set the field's default value |
| `inline`        | `boolean`                               | `checkboxes`, `radio` | Set to `true` to display the fields inline |
| `placeholder`   | `string`                                | `input`, `textarea`   | Set the field's placeholder value |
| `show_label`    | `boolean`                               | All fieldtypes        | Set to `false` to hide the field's label and instructions. This can be useful for single checkboxes, eg. `Accept terms and conditions`. |
| `width`         | `integer`                               | All fieldtypes        | Set the desired width of the field. |

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
