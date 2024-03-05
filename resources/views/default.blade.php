<form
    x-data="form"
    x-effect="processFields($wire.fields)"
    x-cloak
    wire:submit="submit"
>
    <div class="grid gap-y-16">
        @formView('layouts.sections')
        <div class="grid gap-y-4">
            @formView('fields.honeypot')
            @formView('layouts.submit')
            @formView('messages.errors')
            @formView('messages.success')
        </div>
    </div>
</form>

@once
    <script type="module" src="/vendor/livewire-forms/js/livewire-forms.js"></script>
@endonce
