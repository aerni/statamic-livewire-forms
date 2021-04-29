<div style="display: none">
    <input
        type="text"
        name="{{ $honeypot->handle }}"
        id="{{ $honeypot->handle }}"
        wire:model.defer="{{ $honeypot->key }}"
        tabindex="-1"
        autocomplete="off"
    />
</div>
