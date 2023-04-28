@if ($section)

    <div>

        @if ($section['display'])
            <div class="mb-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __($section['display']) }}</h3>

                @if ($section['instructions'])
                    <p class="mt-1 text-sm text-gray-500">{{ __($section['instructions']) }}</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
            @foreach ($section['fields'] as $field)
                @include($this->component->getView('field'))
            @endforeach
        </div>

    </div>

@endif
