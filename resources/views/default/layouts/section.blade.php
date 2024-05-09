<section
    x-show="showSection('{{ $section->handle() }}')"
    wire:key="{{ $section->id() }}"
    class="flex flex-col gap-y-8"

    @if($section->display())
        aria-labelledby="{{ $section->id() }}-label"
    @endif

    @if($section->instructions())
        aria-describedby="{{ $section->id() }}-instructions"
    @endif
>

    @if($section->display())
        <div>
            <h3 id="{{ $section->id() }}-label" class="text-lg font-medium text-gray-900">
                {{ $section->display() }}
            </h3>

            @if($section->instructions())
                <p id="{{ $section->id() }}-instructions" class="text-base text-gray-500">
                    {{ $section->instructions() }}
                </p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
        @foreach($section->fields() as $field)
            @formView('layouts.field')
        @endforeach
    </div>

</section>
