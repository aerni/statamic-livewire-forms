<?php

namespace Aerni\LivewireForms\Livewire\Concerns;

use Aerni\LivewireForms\Fields\Assets;
use Livewire\WithFileUploads as BaseWithFileUploads;

trait WithFileUploads
{
   use BaseWithFileUploads;

   public function restoreUpload(string $tmpFilename): array
   {
       $file = $this->fields->whereInstanceOf(Assets::class)
           ->flatMap(fn ($field) => $field->value())
           ->first(fn ($field) => $tmpFilename === $field->getFilename());

        return [
            'url' => $file->temporaryUrl(),
            'filename' => $file->getClientOriginalName(),
        ];
   }
}
