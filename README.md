# Livewire Statamic Forms

This addon allows you to submit forms in Statamic CMS using Laravel Livewire.

## Installation

Install the package via composer:

```bash
composer require aerni/livewire-forms
```

## Usage

Include Livewire styles and scripts:

```html
<html>
<head>
    <!-- /... -->
    {{ livewire:styles }}
</head>
<body>

    <!-- /... -->
    {{ livewire:scripts }}
</body>
</html>
```

Run command to generate the view:

```bash
php artisan make:statamic-livewire-form-view
```

Or, create the view manually in `/resources/views/livewire/form-view.blade.php`.

Bind properties in the view like this:
```html
<input autocomplete="name" type="text" wire:model.lazy="fields.name" />
@error('fields.name')<div>{{ $message }}</div>@enderror
```

Embed the livewire component in your template:

```html
{{ livewire:livewire-form handle="contact_form" }}
```
If no `view` parameter is set, the component will default to the kebab case of the form handle e.g. `/resources/views/livewire/contact-form.blade.php`

## Notes & Limitations
- Only tested with text and textarea form fieldtypes.
- Not tested on multisites
- Not tested using any [static caching strategies](https://statamic.dev/static-caching#caching-strategies).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
