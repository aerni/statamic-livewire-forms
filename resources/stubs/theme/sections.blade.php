@foreach ($this->fields->sections() as $section)
    @include($this->component->getView('section'))
@endforeach
