@if (isset($section))
    <div class="col-span-1 pt-8 border-t border-gray-200 first:pt-0 first:border-t-0 md:col-span-12">
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("livewire-forms.{$handle}.{$section['handle']}.display") }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __("livewire-forms.{$this->handle}.{$handle}.{$section['handle']}.instructions") }}</p>
        </div>

        <div class="grid grid-cols-1 gap-8 mt-6 md:grid-cols-12">
            @foreach ($section['fields'] as $field)
                @include($this->component->getView('field'))
            @endforeach
        </div>
    </div>
@endif
