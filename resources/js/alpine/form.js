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
                section: field.section
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

    showStep(step) {
        const visible = Object.entries(this.fieldsBySection(step)).some(([field]) => this.fields[field].visible)

        this.$wire.stepVisibility[step] = visible

        // TODO: We are dispatching the event every time this method is triggered.
        // Can we just dispatch it once after all the steps have been processed?
        // TODO: This currently leads to weird behavior as it triggers a component update whenever I type in a wire:model field.
        this.$dispatch('trigger-mutation')

        return visible
    },
})
