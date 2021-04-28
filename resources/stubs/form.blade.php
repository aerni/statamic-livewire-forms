<div>
    <form wire:submit.prevent="submit">
        @include('statamic-livewire-forms::fields')
        @include('statamic-livewire-forms::errors')
        @include('statamic-livewire-forms::success')
    </form>
</div>
