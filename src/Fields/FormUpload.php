<?php

namespace Aerni\LivewireForms\Fields;

use Statamic\Forms\Uploaders\AssetsUploader;
use Statamic\Forms\Uploaders\FilesUploader;

class FormUpload extends Assets
{
    protected function uploader(): string
    {
        return $this->field->get('store') ? AssetsUploader::class : FilesUploader::class;
    }
}
