import FieldConditions from '../../vendor/statamic/cms/resources/js/frontend/components/FieldConditions.js';

export default () => ({
    fields: {},

    conditions: new FieldConditions,

    processFields(fields) {
        const values = Object.entries(fields).reduce((fields, [key, field]) => {
            fields[key] = field.value

            return fields
        }, {})

        this.fields = Object.entries(fields).reduce((fields, [key, field]) => {
            const passesConditions = this.conditions.showField(field.properties.conditions, values)

            fields[key] = {
                visible: passesConditions && !field.properties.hidden,
                submittable: field.properties.always_save || passesConditions
            }

            this.$wire.submittableFields[key] = fields[key].submittable

            return fields
        }, {})
    },

    showField(field) {
        return this.fields[field].visible
    },

    showSection(fields) {
        return Object.entries(fields).some(([field]) => this.fields[field].visible)
    }
})
