<ol class="flex flex-wrap items-center w-full text-sm font-medium gap-y-1.5 gap-x-3">
    @foreach($this->steps as $step)
        <li
            x-show="showStep('{{ $step->handle() }}')"
            wire:key="{{ $step->id() }}"
            class="flex items-center whitespace-nowrap gap-x-3"
        >
            <button
                type="button"
                class="
                    inline-flex transition-all focus:outline-none focus:rounded focus:ring-2 focus:ring-indigo-500
                    @if($step->hasErrors())
                        text-red-600
                    @else
                        {{ $step->isCurrent() ? 'text-indigo-600' : 'text-gray-400 hover:text-indigo-400' }}
                    @endif
                "
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
