<form wire:submit.prevent="submit" class="w-full max-w-2xl">
    <div class="flex flex-col gap-y-16">
        @formSections
        @formHoneypot
        @formSubmit
        @formErrors
        @formSuccess
    </div>
</form>
