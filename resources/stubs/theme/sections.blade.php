@foreach ($this->fields->sections() as $handle => $section)
    @include($this->component->getView('section'))
@endforeach
