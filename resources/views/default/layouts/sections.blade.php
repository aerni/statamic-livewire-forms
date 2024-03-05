<div class="grid gap-y-16">
    @foreach($this->sections as $section)
        @formView('layouts.section')
    @endforeach
</div>
