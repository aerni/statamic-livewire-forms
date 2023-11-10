<form wire:submit="submit">
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
