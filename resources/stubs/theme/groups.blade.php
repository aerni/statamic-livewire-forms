@foreach ($this->fields->groups() as $group => $fields)
    @include($this->component->getView('group'))
@endforeach
