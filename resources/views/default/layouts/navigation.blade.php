<ol class="flex flex-wrap items-center w-full text-sm font-medium gap-y-1.5 gap-x-3">
    @foreach($this->steps as $step)
        <li
            x-show="showStep('{{ $step->handle() }}')"
            wire:key="{{ $step->id() }}"
            class="whitespace-nowrap flex items-center gap-x-3
                @if($step->hasErrors())
                    text-red-600
                @else
                    {{ $step->isCurrent() ? 'text-indigo-600' : 'text-gray-400' }}
                @endif
            "
        >
            <button
                type="button"
                class="flex items-center"
                @if($this->canNavigateToStep($step->number()))
                    wire:click="{{ $step->show() }}"
                @else
                    disabled
                @endif
            >
                {{ $step->display() }}
            </button>

            @if(!$loop->last)
                <span class="text-gray-300 select-none" aria-hidden="true">/</span>
            @endif
        </li>
    @endforeach
</ol>
