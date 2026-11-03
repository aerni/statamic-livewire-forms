<div class="flex flex-col gap-y-1">
    <h2 id="{{ $field->id }}" class="text-lg font-semibold text-gray-900">
        {{ $field->display }}
    </h2>

    @if($field->subheading)
        <p class="text-sm text-gray-500">
            {{ $field->subheading }}
        </p>
    @endif
</div>
