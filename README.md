![Statamic](https://flat.badgen.net/badge/Statamic/3.0+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/livewire-forms/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/livewire-forms)

# Livewire Forms
This addon allows you to use Statamic forms with Laravel Livewire.

## Features
- Use your Statamic forms with Laravel Livewire
- Use your Statamic form blueprint as a form builder
- Realtime validation
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

## Basic Usage

Include Livewire styles and scripts:

```html
<html>
    <head>
        {{ livewire:styles }}
    </head>

    <body>
        {{ livewire:scripts }}
    </body>
</html>
```

### Create Form

Create a Livewire form view with this command and follow the instructions. The form view comes configured, styled, and is ready to go. You're free to change it however you'd like.

```bash
php please livewire-form:make
```

### Render Form

Include the Livewire form component in your template and provide the handle of the Statamic form. This will automatically load the corresponding form view in `views/livewire/my-form-handle.{antlers.html|blade.php}`.

```html
<!-- Antlers -->
{{ livewire:form form="contact" }}

<!-- Blade -->
<livewire:form form="contact">
```

You can also dynamically render a form that was selected via the Form Fieldtype:

```html
<!-- Antlers -->
{{ livewire:form :form="fieldtype:handle" }}

<!-- Blade -->
<livewire:form :form="fieldtype:handle">
```

### Change View

You can include a single field like this:

```html
<!-- Antlers -->
{{ partial src="livewire-forms::fields" field="name" }}

<!-- Blade -->
@include('livewire-forms::fields', [
    'field' => $fields['name'],
])
```

### Realtime Validation

You can configure realtime validation on three levels:
1. In the global config at `config/livewire-forms.php`
2. On the form blueprint
3. On the form field


**Form Blueprint**
```yaml
sections:
  main:
    display: Main
    fields:
      -
        handle: email
        field:
          input_type: email
          antlers: false
          display: Email
          type: text
          icon: text
          listable: hidden
          validate:
            - required
            - email
```
