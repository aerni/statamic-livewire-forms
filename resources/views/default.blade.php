<form
    x-data="form"
    x-effect="processFields($wire.fields)"
    x-cloak
    wire:submit="submit(submittableFields())"
>
    <div class="grid gap-y-16">
        @formView('layouts.sections')
        <div class="grid gap-y-4">
            @formView('fields.honeypot')
            @formView('layouts.submit')
            @formView('messages.errors')
            @formView('messages.success')
        </div>
    </div>
</form>

@script
    <script>
        Alpine.data('form', () => {
            return {
                fields: {},

                processFields(fields) {
                    this.fields = Object.entries(fields).reduce((fields, [key, field]) => {
                        fields[key] = {
                            value: field.value,
                            conditions: field.properties.conditions ?? [],
                            visible: true,
                            hidden: field.properties.hidden,
                        }
                        return fields;
                    }, {});
                },

                showField(field) {
                    return this.fields[field].visible = this.fields[field].hidden
                        ? false // We never want to show explicitly hidden fields.
                        : Statamic.$conditions.showField(this.fields[field].conditions, this.values())
                },

                showSection(fields) {
                    return Object.entries(fields).some(([field]) => this.fields[field].visible)
                },

                values() {
                    return Object.entries(this.fields).reduce((fields, [key, field]) => {
                        fields[key] = field.value
                        return fields;
                    }, {});
                },

                submittableFields() {
                    return Object.entries(this.fields).reduce((fields, [key, field]) => {
                        fields[key] = field.visible
                        return fields;
                    }, {});
                },
            }
        })
    </script>
@endscript

@once
    <script src="/vendor/statamic/frontend/js/helpers.js"></script>
@endonce
