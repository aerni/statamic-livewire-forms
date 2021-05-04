<div class="hidden col-span-1">

    <label for="{{ $honeypot['handle'] }}">
        {{ $honeypot['label'] }}
    </label>

    <input
        type="text"
        name="{{ $honeypot['handle'] }}"
        id="{{ $honeypot['handle'] }}"
        wire:model.defer="{{ $honeypot['key'] }}"
        tabindex="-1"
        autocomplete="off"
    />

</div>
