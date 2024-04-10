import FieldConditions from '../../../vendor/statamic/cms/resources/js/frontend/components/FieldConditions.js';

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
                submittable: field.properties.always_save || passesConditions,
                section: field.section,
                config: field.properties
            }

            this.$wire.submittableFields[key] = fields[key].submittable

            return fields
        }, {})
    },

    fieldsBySection(section) {
        return Object.entries(this.fields).reduce((sections, [key, field]) => {
            let section = field.section;

            if (! sections[section]) {
                sections[section] = {};
            }

            sections[section][key] = field;

            return sections;
        }, {})[section];
    },

    showField(field) {
        return this.fields[field].visible
    },

    showSection(section) {
        return Object.entries(this.fieldsBySection(section)).some(([field]) => this.fields[field].visible)
    },
})
