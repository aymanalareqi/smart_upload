<?php

namespace Alareqi\SmartUpload\Http\Controllers;

use Alareqi\SmartUpload\Support\FileUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TemporaryUploadController extends Controller
{
    protected FileUploader $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function uploadFile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => config('smart-upload.temporary_file_upload.rules'),
        ]);

        $file = $validated['file'];
        $result = $this->uploader->uploadFile($file);

        return response()->json($result);
    }
}
