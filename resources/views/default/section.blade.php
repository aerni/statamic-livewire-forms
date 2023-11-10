@if($section)

    <section
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
                @include($this->component->getView('field'))
            @endforeach
        </div>

    </section>

@endif