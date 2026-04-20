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
            'filename' => 'required|string',
        ]);

        $result = $this->uploader->uploadFile($validated);

        return response()->json($result);
    }
}
