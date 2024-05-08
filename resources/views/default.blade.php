<form
    x-data="form"
    x-effect="processFields($wire.fields)"
    x-cloak
    wire:submit="submit"
>
    @formView("forms.{$this->type}")
</form>

@assets
    <script type="module" src="/vendor/livewire-forms/js/livewire-forms.js"></script>
@endassets
