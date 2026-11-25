import FieldConditions from '../../vendor/statamic/cms/resources/js/frontend/components/FieldConditions.js'

Alpine.data('form', () => ({
    fields: {},
    sections: {},

    processForm() {
        this.fields = this.processFields(this.$wire.fields)
        this.sections = this.processSections(this.fields)
    },

    processFields(fields) {
        const entries = Object.entries(fields)

        const values = Object.fromEntries(entries.map(([handle, field]) => [handle, field.value]))

        return entries.reduce((processed, [handle, field]) => this.processField({
            conditions: field.properties.conditions,
            hidden: field.properties.hidden,
            always_save: field.properties.always_save,
            fields: field.properties.fields,
            section: field.section,
        }, handle, values, processed), {})
    },

    processField(field, path, values, processed) {
        const passesConditions = new FieldConditions().showField(field.conditions, values, path)

        processed[path] = {
            visible: passesConditions && !field.hidden,
            submittable: field.always_save || passesConditions,
            section: field.section ?? null,
        }

        this.$wire.submittableFields[path] = processed[path].submittable

        field.fields?.forEach((child) => this.processField(
            child,
            `${path}.${child.handle}`,
            values,
            processed,
        ))

        return processed
    },

    processSections(fields) {
        const visibleFieldsBySection = Object.entries(fields).reduce((sections, [key, field]) => {
            if (field.section) {
                sections[field.section] = sections[field.section] || []
                sections[field.section].push(field.visible)
            }

            return sections
        }, {})

        const sectionVisibility = Object.fromEntries(
            Object.entries(visibleFieldsBySection).map(([section, visibilities]) => [
                section,
                visibilities.some(Boolean),
            ])
        )

        if (this.$wire.type === 'wizard' && JSON.stringify(sectionVisibility) !== JSON.stringify(this.$wire.stepVisibility)) {
            this.$wire.stepVisibility = sectionVisibility
            this.$wire.$refresh() // We need to refresh so that the "Send" button shows up instead of "Continue" if next steps are conditionally hidden.
        }

        return sectionVisibility
    },

    showField(field) {
        return this.fields[field].visible
    },

    showSection(section) {
        return this.sections[section]
    },

    showStep(step) {
        return this.sections[step]
    },
}))
