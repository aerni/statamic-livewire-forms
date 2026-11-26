@if($field->html)
    <div id="{{ $field->id }}" class="text-sm text-gray-700">
        {!! $field->html !!}
    </div>
@else
    <p id="{{ $field->id }}" class="text-sm text-gray-700">
        {{ $field->text }}
    </p>
@endif
