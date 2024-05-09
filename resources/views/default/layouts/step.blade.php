<section
    x-show="showStep('{{ $step->handle() }}')"
    wire:key="{{ $step->id() }}"
    class="flex flex-col p-8 bg-white border rounded-lg gap-y-8"

    @if($step->display())
        aria-labelledby="{{ $step->id() }}-label"
    @endif

    @if($step->instructions())
        aria-describedby="{{ $step->id() }}-instructions"
    @endif
>

    @if($step->display())
        <div>
            <h3 id="{{ $step->id() }}-label" class="text-lg font-medium text-gray-900">
                {{ $step->display() }}
            </h3>

            @if($step->instructions())
                <p id="{{ $step->id() }}-instructions" class="text-base text-gray-500">
                    {{ $step->instructions() }}
                </p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
        @foreach($step->fields() as $field)
            @formView('layouts.field')
        @endforeach
    </div>

    <div class="flex flex-row-reverse flex-wrap items-center justify-start gap-x-3 gap-y-2">
        @if($this->hasNextStep)
            @formView('buttons.next')
        @else
            @formView('buttons.submit')
        @endif

        @if($this->hasPreviousStep)
            @formView('buttons.previous')
        @endif
    </div>

</section>
