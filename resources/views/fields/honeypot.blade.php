<div class="hidden col-span-1">

    <label for="{{ $field['handle'] }}">
        {{ $field['label'] }}
    </label>

    <input
        type="text"
        name="{{ $field['handle'] }}"
        id="{{ $field['handle'] }}"
        wire:model.defer="{{ $field['key'] }}"
        tabindex="-1"
        autocomplete="off"
    />

</div>
