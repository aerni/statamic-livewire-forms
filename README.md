![Statamic](https://flat.badgen.net/badge/Statamic/3.0+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/livewire-forms/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/livewire-forms)

# Livewire Forms
This addon allows you to use Statamic forms with Laravel Livewire.

## Features
- Use your Statamic forms with Laravel Livewire
- Use your Statamic form blueprint as a form builder
- Realtime validation with fine-grained control for each field
- No more dealing with Front End Validation libraries
- Support for Antlers and Blade
- Honeypot field for Spam protection
- No redirects and pageloads
- Prestyled fields ready to go

## Installation
Install the addon using Composer:

```bash
composer require aerni/livewire-forms
```

Publish the config of the package:

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

## Configuration
XXX
Add @tailwindcss/forms

## Basic usage

### 1. Include Livewire

Add the Livewire `styles` in the `head`, and the `scripts` before the closing `body` tag in your template.

**Antlers**
```php
<head>
    {{ livewire:styles }}
</head>

<body>
    {{ livewire:scripts }}
</body>
```

**Blade**
```php
<head>
    @livewireStyles
</head>

<body>
    @livewireScripts
</body>
```

### 2. Create a Statamic form

Go ahead and create a Statamic form in the Control Panel.

### 3. Create a Livewire form view

Run the following command and follow the instructions to create a Livewire view for your Statamic form. The view will be published to `views/livewire/my-form-handle.{antlers.html|blade.php}`.

```bash
php please make:livewire-form
```

You may choose to publish the default form views to change the markup and styling of the form fields. The views will be published to `views/vendor/livewire-forms`.

### 4. Render the form

Include the Livewire form component in your template and provide the handle of the Statamic form. This will automatically load the corresponding form view.

**Antlers**
```php
{{ livewire:form form="contact" }}
```

**Blade**
```php
<livewire:form form="contact">
```

You can also dynamically render a form that was selected via Statamic's `form` fieldtype:

**Antlers**
```php
{{ livewire:form :form="field:handle" }}
```

**Blade**
```php
<livewire:form :form="field:handle">
```

## Customize the form view

Sometimes you need more control over your form, eg. to group specific fields in a `<fieldset>`. You can include single fields like this:

**Antlers**
```php
{{ partial src="livewire-forms::fields" field="name" }}
```

**Blade**
```php
@include('livewire-forms::fields', [
    'field' => $fields['name'],
])
```

## Form configuration

This addon provides multiple configuration options for your form fields.

```
show_label
cast_booleans
honeypot
```

### Realtime validation

You can configure realtime validation on three levels. In the config file, on the form, and on the form field. Each level will override the configuration of the previous level.

#### 1. In the config
A boolean to globally enable/disable realtime validation.

```php
// config/livewire-forms.php

'realtime' => true,
```

#### 2. On the form
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

#### 3. On the form field
You have to options when configuring realtime validation on a specific field.

**Option 1**

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

**Option 2**

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
