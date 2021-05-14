![Statamic](https://flat.badgen.net/badge/Statamic/3.0+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/livewire-forms/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/livewire-forms)

# Livewire Forms
This addon allows you to submit your Statamic forms with Laravel Livewire.

## Features
- Realtime validation with fine-grained control over each field
- No need for a client-side form validation library
- One source of truth for your validation rules
- No redirects after the form was submitted
- Honeypot field for simple and effective spam prevention
- Use your Statamic form blueprint as a form builder
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

];
```

Publish the form views to customize the styling to your liking (optional):

```bash
# Publish the Antlers views
php please vendor:publish --tag=livewire-forms-antlers

# Publish the Blade views
php please vendor:publish --tag=livewire-forms-blade
```

The views will be published to `views/vendor/livewire-forms`.

> **Important:** The default form views are styled with [Tailwind CSS](https://tailwindcss.com/). If you want to use the default styling, you need a working Tailwind setup with the [@tailwindcss/forms](https://github.com/tailwindlabs/tailwindcss-forms) plugin.

## Basic usage

### 1. Create a Statamic form

Go ahead and create a Statamic form in the Control Panel.

### 2. Include Livewire

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

### 3. Create a Livewire form view

Run the following command and follow the instructions to create a Livewire view for your Statamic form. The form view will be published to `views/livewire/my-form-handle.{antlers.html|blade.php}`.

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
You have to options when configuring realtime validation on a specific field.

#### Option 1
Use a boolean to enable/disable realtime validation for the field

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

#### Option 2
Provide an array with the rules you want to validate in realtime.

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

This addon provides multiple configuration options for your form fields.

### Data specific

Use these options to change how your field values will be saved.

| Parameter       | Type      | Supported by          | Description                |
| :-------------- | :-------- | :-------------------- | :------------------------- |
| `cast_booleans` | `boolean` | All fieldtypes        | Save the value as a boolean |

### Layout specific

Use these options to change how your fields render on the front-end.

| Parameter       | Type      | Supported by          | Description                |
| :-------------- | :-------- | :-------------------- | :------------------------- |
| `width`         | `integer` | All fieldtypes        | Set the desired width of the field. |
| `show_label`    | `boolean` | `checkboxes`, `radio` | Set to `false` to hide the field's label and instructions. This can be useful for single checkboxes, eg. `Accept terms and conditions`. |
| `inline`        | `boolean` | `checkboxes`, `radio` | Set to `true` to display the fields inline |

> **Important:** These options may not work correctly if you changed the default form views.
