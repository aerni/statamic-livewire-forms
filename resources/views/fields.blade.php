@foreach ($this->fields->all() as $field)
    @include($this->component->getView('field'))
@endforeach
