<form wire:submit.prevent="submit" class="w-full max-w-2xl">
    <div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-12">
        @include('livewire-forms::fields')
        @include('livewire-forms::fields.submit')
        @include('livewire-forms::fields.honeypot')
        @include('livewire-forms::errors')
        @include('livewire-forms::success')
    </div>
</form>
