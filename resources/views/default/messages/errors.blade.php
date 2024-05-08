@if($errors->any())

    <div class="flex w-full p-4 border-2 border-red-500 rounded-md bg-red-50">
        <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-base font-medium text-red-800">
                {{ $this->errorMessage() }}
            </h3>
            <div class="mt-2 text-sm text-red-600">
                <ul class="pl-4 space-y-1 list-disc">
                    @foreach($errors->all() as $error)
                        <li wire:key="error-{{ Str::slug($error) }}">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endif
