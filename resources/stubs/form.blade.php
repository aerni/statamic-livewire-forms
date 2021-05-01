<form wire:submit.prevent="submit" class="w-full max-w-2xl">
    <div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-12">
        @include('statamic-livewire-forms::fields')
        @include('statamic-livewire-forms::fields.submit')
        @include('statamic-livewire-forms::fields.honeypot')
        @include('statamic-livewire-forms::errors')
        @include('statamic-livewire-forms::success')
    </div>
</form>
