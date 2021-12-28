@foreach ($fields->groupBy('group')->only($group) as $fields)
    <fieldset class="col-span-1 md:col-span-12">
        <div class="mb-3">
            <legend class="text-base font-medium text-gray-700">{{ __("forms.$group.title") }}</legend>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
            @formFields
        </div>
    </fieldset>
@endforeach
