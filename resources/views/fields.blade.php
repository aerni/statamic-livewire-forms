@foreach ($this->fields->all() as $field)
    @include($this->view->get('field'))
@endforeach
