<form
    x-data="form"
    x-effect="processForm"
    x-cloak
    wire:submit="submit"
>
    @formView("forms.{$this->type}")
</form>

@assets
    @if($this->hasAssetsField)
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    @endif
    <script type="module" src="/vendor/livewire-forms/js/livewire-forms.js"></script>
@endassets
