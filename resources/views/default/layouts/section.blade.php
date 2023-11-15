<section
    x-data="{
        fields: {{ $section['fields'] }},
        init() {
            this.fields = Object.fromEntries(Object.keys(this.fields).map(key => [key, true]))
        },
        updateFields(event) {
            if (this.fields.hasOwnProperty(event.detail.field)) {
                this.fields[event.detail.field] = event.detail.evaluation
            }
        },
        show() {
            return Object.values(this.fields).some(value => value === true)
        },
    }"
    x-show="show"
    x-on:field-conditions-evaluated="updateFields($event)"
    wire:key="{{ $section['id'] }}"
    aria-labelledby="{{ $section['id'] }}-label"
    aria-describedby="{{ $section['id'] }}-instructions"
    class="grid gap-y-8"
>

    @if($section['display'])
        <div>
            <h3 id="{{ $section['id'] }}-label" class="text-lg font-medium text-gray-900">
                {{ __($section['display']) }}
            </h3>

            @if($section['instructions'])
                <p id="{{ $section['id'] }}-instructions" class="text-base text-gray-500">
                    {{ __($section['instructions']) }}
                </p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
        @foreach($section['fields'] as $field)
            @formView('layouts.field')
        @endforeach
    </div>

</section>
