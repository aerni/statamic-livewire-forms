<div class="flex flex-col gap-y-16">
    @foreach($this->sections as $section)
        @formView("layouts.section")
    @endforeach
    <div class="flex flex-col items-start gap-y-4">
        @formView('buttons.submit')
        @formView('messages.errors')
        @formView('messages.success')
        @formView('fields.honeypot')
    </div>
</div>
