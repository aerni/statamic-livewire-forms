<div class="flex flex-col gap-y-8">
    @foreach ($this->fields->sections() as $section)
        @include($this->component->getView('section'))
    @endforeach
</div>
