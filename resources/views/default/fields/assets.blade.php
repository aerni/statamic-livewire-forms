<div>
    @formView('messages.label')

    @if($field->instructions && $field->instructions_position === 'above')
        @formView('messages.instructions')
    @endif
</div>

<div
    id="{{ $field->id }}"
    @if(! $errors->has($field->key))
        @if($field->instructions)
            aria-describedby="{{ $field->id }}-instructions"
        @endif
    @else
        aria-invalid="true"
        aria-describedby="{{ $field->id }}-error"
    @endif
>
    <div
        x-data="filepond({
            field: '{{ $field->handle }}',
            locale: @antlers'{{ site:attributes:filepond_locale ?? 'en-en' }}'@endantlers,
        })"
        x-on:form-reset.window="reset($event.detail.id)"
        wire:ignore
    >
        <input type="file" x-ref="input" />
    </div>
</div>

@if($errors->has($field->key))
    @formView('messages.error')
@elseif($field->instructions && $field->instructions_position === 'below')
    @formView('messages.instructions')
@endif

@assets
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
@endassets
