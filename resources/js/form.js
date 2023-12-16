import FieldConditions from '../../vendor/statamic/cms/resources/js/frontend/components/FieldConditions.js';

export default () => ({
    fields: {},

    conditions: new FieldConditions,

    processFields(fields) {
        this.fields = Object.entries(fields).reduce((field, [key, value]) => {
            field[key] = {
                value: value.value,
                conditions: value.properties.conditions ?? [],
                visible: true,
            }
            return field;
        }, {});
    },

    showField(field) {
        return this.fields[field].visible = this.conditions.showField(this.fields[field].conditions, this.values())
    },

    showSection(fields) {
        return Object.entries(fields).some(([field]) => this.fields[field].visible)
    },

    values() {
        return Object.entries(this.fields).reduce((field, [key, value]) => {
            field[key] = value.value
            return field;
        }, {});
    },

    submittableFields() {
        return Object.entries(this.fields).reduce((field, [key, value]) => {
            field[key] = value.visible
            return field;
        }, {});
    },
})
