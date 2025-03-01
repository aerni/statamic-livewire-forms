<form
    x-data="form"
    x-effect="processForm"
    x-cloak
    wire:submit="submit"
>
    @formView("forms.{$this->type}")
</form>

@formAssets
