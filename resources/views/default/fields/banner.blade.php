<div id="{{ $field->id }}" class="flex items-start gap-x-3 p-4 bg-gray-50 border border-gray-200 rounded-md">
    @if($field->icon)
        <div class="w-6 h-6 shrink-0 text-gray-500 [&_svg]:w-full [&_svg]:h-full">
            {!! $field->icon !!}
        </div>
    @endif

    <div class="flex flex-col gap-y-1">
        <h3 class="text-sm font-medium text-gray-900">
            {{ $field->display }}
        </h3>

        @if($field->text)
            <p class="text-sm text-gray-500">
                {{ $field->text }}
            </p>
        @endif
    </div>
</div>
