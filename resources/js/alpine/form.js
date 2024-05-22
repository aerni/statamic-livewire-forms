import FieldConditions from '../../../vendor/statamic/cms/resources/js/frontend/components/FieldConditions.js'

export default () => ({
    fields: {},
    sections: {},

    processForm() {
        this.fields = this.processFields(this.$wire.fields)
        this.sections = this.processSections(this.fields)
    },

    processFields(fields) {
        const values = Object.entries(fields).reduce((fields, [key, field]) => {
            fields[key] = field.value

            return fields
        }, {})

        return Object.entries(fields).reduce((fields, [key, field]) => {
            const passesConditions = new FieldConditions().showField(field.properties.conditions, values)

            fields[key] = {
                visible: passesConditions && !field.properties.hidden,
                submittable: field.properties.always_save || passesConditions,
                section: field.section
            }

            this.$wire.submittableFields[key] = fields[key].submittable

            return fields
        }, {})
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

        if (JSON.stringify(sectionVisibility) !== JSON.stringify(this.$wire.stepVisibility)) {
            this.$wire.stepVisibility = sectionVisibility
            this.$wire.$refresh()
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
})
