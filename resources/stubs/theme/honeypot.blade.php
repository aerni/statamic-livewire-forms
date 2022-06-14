<div class="hidden">

    <label for="{{ $this->honeypot->id }}">
        {{ $this->honeypot->label }}
    </label>

    <input
        type="text"
        name="{{ $this->honeypot->id }}"
        id="{{ $this->honeypot->id }}"
        wire:model.defer="{{ $this->honeypot->key }}"
        tabindex="-1"
        autocomplete="off"
    />

</div>
