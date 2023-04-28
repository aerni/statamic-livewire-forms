<form wire:submit.prevent="submit">
    <div class="flex flex-col w-full max-w-2xl gap-y-16">
        @formSections
        <div class="flex flex-col gap-y-4">
            @formHoneypot
            @formSubmit
            @formErrors
            @formSuccess
        </div>
    </div>
</form>
