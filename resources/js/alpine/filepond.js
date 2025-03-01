import * as FilePond from 'filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageValidateSize from 'filepond-plugin-image-validate-size';
import ar_AR from 'filepond/locale/ar-ar';
import az_AZ from 'filepond/locale/az-az';
import cs_CZ from 'filepond/locale/cs-cz';
import da_DK from 'filepond/locale/da-dk';
import de_DE from 'filepond/locale/de-de';
import el_EL from 'filepond/locale/el-el';
import en_EN from 'filepond/locale/en-en';
import es_ES from 'filepond/locale/es-es';
import fa_IR from 'filepond/locale/fa_ir';
import fi_FI from 'filepond/locale/fi-fi';
import fr_FR from 'filepond/locale/fr-fr';
import he_HE from 'filepond/locale/he-he';
import hr_HR from 'filepond/locale/hr-hr';
import hu_HU from 'filepond/locale/hu-hu';
import id_ID from 'filepond/locale/id-id';
import it_IT from 'filepond/locale/it-it';
import ja_JA from 'filepond/locale/ja-ja';
import km_KM from 'filepond/locale/km-km';
import lt_LT from 'filepond/locale/lt-lt';
import nl_NL from 'filepond/locale/nl-nl';
import no_NB from 'filepond/locale/no_nb';
import pl_PL from 'filepond/locale/pl-pl';
import pt_BR from 'filepond/locale/pt-br';
import pt_PT from 'filepond/locale/pt-pt';
import ro_RO from 'filepond/locale/ro-ro';
import ru_RU from 'filepond/locale/ru-ru';
import sk_SK from 'filepond/locale/sk-sk';
import sv_SE from 'filepond/locale/sv_se';
import tr_TR from 'filepond/locale/tr-tr';
import uk_UA from 'filepond/locale/uk-ua';
import vi_VI from 'filepond/locale/vi-vi';
import zh_CN from 'filepond/locale/zh-cn';
import zh_TW from 'filepond/locale/zh-tw';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

const locales = {
    'ar-ar': ar_AR,
    'az-az': az_AZ,
    'cs-cz': cs_CZ,
    'da-dk': da_DK,
    'de-de': de_DE,
    'el-el': el_EL,
    'en-en': en_EN,
    'es-es': es_ES,
    'fa-ir': fa_IR,
    'fi-fi': fi_FI,
    'fr-fr': fr_FR,
    'he-he': he_HE,
    'hr-hr': hr_HR,
    'hu-hu': hu_HU,
    'id-id': id_ID,
    'it-it': it_IT,
    'ja-ja': ja_JA,
    'km-km': km_KM,
    'lt-lt': lt_LT,
    'nl-nl': nl_NL,
    'no-nb': no_NB,
    'pl-pl': pl_PL,
    'pt-br': pt_BR,
    'pt-pt': pt_PT,
    'ro-ro': ro_RO,
    'ru-ru': ru_RU,
    'sk-sk': sk_SK,
    'sv-se': sv_SE,
    'tr-tr': tr_TR,
    'uk-ua': uk_UA,
    'vi-vi': vi_VI,
    'zh-cn': zh_CN,
    'zh-tw': zh_TW,
};

export default (config) => ({
    init() {
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginImageValidateSize);

        const field = this.$wire.fields[config.field].properties;

        FilePond.create(this.$refs.input, {
            allowMultiple: field.multiple,
            minFileSize: field.file_size.min ? `${field.file_size.min}KB` : null,
            maxFileSize: field.file_size.max ? `${field.file_size.max}KB` : null,
            acceptedFileTypes: field.file_types,
            imageValidateSizeMinWidth: field.dimensions.min_width ?? 1,
            imageValidateSizeMinHeight: field.dimensions.min_height ?? 1,
            imageValidateSizeMaxWidth: field.dimensions.max_width ?? 65535,
            imageValidateSizeMaxHeight: field.dimensions.max_height ?? 65535,
            credits: false,
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    this.$wire.upload(field.key, file, load, error, progress)
                },
                revert: (filename, load) => {
                    this.$wire.removeUpload(field.key, filename, load)
                },
            },
            ...locales[config.locale],
        });
    },

    reset(livewireId) {
        // Only reset the FilePond instance if the form-success event was fired by the same Livewire component
        if (livewireId !== this.$wire.id) return;

        FilePond.find(this.$el.querySelector('.filepond--root')).removeFiles();
    }
})
