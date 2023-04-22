<form wire:submit.prevent="submit" class="w-full max-w-2xl">
    <div class="grid grid-cols-1 gap-8">
        @formSections
        @formHoneypot
        @formSubmit
        @formErrors
        @formSuccess
    </div>
</form>
