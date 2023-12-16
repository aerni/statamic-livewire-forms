import FieldConditions from '../../vendor/statamic/cms/resources/js/frontend/components/FieldConditions.js';

export default () => ({
    fields: {},

    conditions: new FieldConditions,

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
            : this.conditions.showField(this.fields[field].conditions, this.values())
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
})
