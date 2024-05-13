<button
    type="button"
    wire:click="previousStep"
    wire:loading.delay.attr="disabled"
    wire:loading.delay.class="cursor-not-allowed opacity-60 pl-11"
    wire:target="previousStep"
    class="relative inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 transition-all ease-in-out border border-transparent rounded-md hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
>
    <svg
        class="absolute left-0 w-5 h-5 ml-3 text-gray-700 animate-spin"
        wire:loading.delay
        wire:target="previousStep"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
    >
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    {{ __('Back') }}
</button>
