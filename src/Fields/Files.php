<?php

namespace Aerni\LivewireForms\Fields;

use Statamic\Forms\Uploaders\FilesUploader;

class Files extends Assets
{
    protected function uploader(): string
    {
        return FilesUploader::class;
    }
}
