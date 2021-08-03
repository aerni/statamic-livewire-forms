<form wire:submit.prevent="submit" class="w-full max-w-2xl">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
        @include('livewire-forms::fields')
        @include('livewire-forms::submit')
        @include('livewire-forms::errors')
        @include('livewire-forms::success')
    </div>
</form>
