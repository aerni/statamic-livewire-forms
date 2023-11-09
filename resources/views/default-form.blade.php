<form wire:submit="submit">
    <div class="grid gap-y-16">
        @formSections
        <div class="grid gap-y-4">
            @formHoneypot
            @formSubmit
            @formErrors
            @formSuccess
        </div>
    </div>
</form>
