<div class="grid gap-y-16">
    @foreach($this->fields->sections() as $section)
        @include($this->component->getView('layouts.section'))
    @endforeach
</div>
