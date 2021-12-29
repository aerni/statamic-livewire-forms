@foreach ($this->fields->group($group) as $fields)
    <div class="col-span-1 pt-8 border-t border-gray-200 first:pt-0 first:border-t-0 md:col-span-12">
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("livewire-forms.$handle.$group.title") }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __("livewire-forms.$handle.$group.description") }}</p>
        </div>

        <div class="grid grid-cols-1 gap-8 mt-6 md:grid-cols-12">
            @foreach ($fields as $field)
                @include('livewire-forms::field')
            @endforeach
        </div>
    </div>
@endforeach
