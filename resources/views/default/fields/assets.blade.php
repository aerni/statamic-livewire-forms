@formView('messages.display')

@if($field->instructions && $field->instructions_position === 'above')
    @formView('messages.instructions')
@endif

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
        x-on:form-success.window="reset($event.detail.id)"
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
