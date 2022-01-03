@foreach ($this->fields->groups() as $group => $fields)
    @include($this->view->get('group'))
@endforeach
