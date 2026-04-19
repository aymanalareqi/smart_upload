<?php

namespace Alareqi\SmartUpload\Concerns;

use Alareqi\SmartUpload\Support\FileUploader;
use Illuminate\Http\Request;

trait HasFileUploads
{
    protected FileUploader $fileUploader;

    protected function getFileUploader(): FileUploader
    {
        if (! isset($this->fileUploader)) {
            $this->fileUploader = app(FileUploader::class);
        }

        return $this->fileUploader;
    }

    protected function convertUpload(string $uuid, string $directory, ?string $filename = null): string
    {
        return $this->getFileUploader()->convert($uuid, $directory, $filename);
    }

    protected function getUploadUuidsFromRequest(Request $request, array $fields): array
    {
        $uuids = [];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $uuids[$field] = $request->input($field);
            }
        }

        return $uuids;
    }
}