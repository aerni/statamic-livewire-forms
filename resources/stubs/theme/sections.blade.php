@foreach ($this->fields->sections() as $section => $fields)
    @include($this->component->getView('section'))
@endforeach
