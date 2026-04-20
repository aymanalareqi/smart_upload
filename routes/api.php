<?php

use Alareqi\SmartUpload\Http\Controllers\TemporaryUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/upload-file', [TemporaryUploadController::class, 'uploadFile']);
});
