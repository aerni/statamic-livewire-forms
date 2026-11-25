@php
    $states = [
        'notice' => 'bg-gray-50 border-gray-200 text-gray-500',
        'tip' => 'bg-indigo-50 border-indigo-200 text-indigo-600',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
        'important' => 'bg-red-50 border-red-200 text-red-700',
        'success' => 'bg-green-50 border-green-200 text-green-700',
    ];

    $state = $states[$field->state] ?? $states['notice'];
@endphp

<div id="{{ $field->id }}" class="flex items-start gap-x-3 p-4 border rounded-md {{ $state }}">
    @if($field->icon)
        <div class="w-6 h-6 shrink-0 [&_svg]:w-full [&_svg]:h-full">
            {!! $field->icon !!}
        </div>
    @endif

    <div class="flex flex-col gap-y-1 text-sm">
        @unless($field->hide_display)
            <h3 class="font-medium text-gray-900">
                {{ $field->display }}
            </h3>
        @endunless

        @if($field->content)
            <div class="text-gray-700 [&_p]:m-0">
                {!! \Statamic\Facades\Markdown::parse($field->content) !!}
            </div>
        @endif
    </div>
</div>
